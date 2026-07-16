<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Barbearia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Pega o usuário logado (barbeiro)
        $user = Auth::user()?->user;

        // Fallback temporário caso não esteja logado
        if (!$user) {
            $user = \App\Models\User::first();
        }

        if (!$user) {
            return view('users.index')->with('error', 'Nenhum barbeiro encontrado.');
        }

        $hoje = \Carbon\Carbon::today();

        // Atendimentos de hoje deste barbeiro
        $atendimentosHoje = \App\Models\Atendimento::with(['service', 'pagamento'])
            ->where('user_id', $user->id)
            ->where('horario', '>=', $hoje)
            ->orderBy('horario', 'desc')
            ->get();

        $faturadoHoje = $atendimentosHoje->sum('valor');
        $qtdAtendimentosHoje = $atendimentosHoje->count();
        $ticketMedioHoje = $qtdAtendimentosHoje > 0 ? $faturadoHoje / $qtdAtendimentosHoje : 0;

        // Dados para o modal de novo atendimento (3 toques)
        $servicos = \App\Models\Service::all();
        $pagamentos = \App\Models\Pagamento::all();

        return view('pages.userDashboard', compact(
            'user',
            'atendimentosHoje',
            'faturadoHoje',
            'qtdAtendimentosHoje',
            'ticketMedioHoje',
            'servicos',
            'pagamentos'
        ));
    }

    public function create()
    {
        $barbearias = Barbearia::all();

        return view('users.create', compact('barbearias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barbearia_id' => 'nullable|exists:barbearias,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'isactive' => 'boolean',
        ]);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $barbearias = Barbearia::all();

        return view('users.edit', compact('user', 'barbearias'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'barbearia_id' => 'nullable|exists:barbearias,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'isactive' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso!');
    }

    // Atendimentos responsability
    public function storeAtendimento(\Illuminate\Http\Request $request, \App\Models\User $user)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'pagamento_id' => 'required|exists:pagamentos,id',
            'valor' => 'required|numeric'
        ]);

        $data['user_id'] = $user->id;
        // Se a hora não for enviada no formulário, assumimos o momento atual
        $data['horario'] = $request->input('horario', now());
        
        \App\Models\Atendimento::create($data);

        return redirect()->back()->with('success', 'Atendimento registrado com sucesso!');
    }
}
