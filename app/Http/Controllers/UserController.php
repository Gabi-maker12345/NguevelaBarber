<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Barbearia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Por favor, faça login para acessar o painel.');
        }

        $hoje = \Carbon\Carbon::today();

        // Atendimentos de hoje deste barbeiro
        $atendimentosHoje = Atendimento::with(['service', 'pagamento'])
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

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        $this->authorizeWebUser($user);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeWebUser($user);

        $barbearias = Barbearia::all();
        return view('users.edit', compact('user', 'barbearias'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeWebUser($user);

        $data = $request->validate([
            'barbearia_id' => 'nullable|exists:barbearias,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'isactive' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        abort(403);
    }

    public function storeForBarbearia(Request $request, Barbearia $barbearia)
    {
        $this->authorizeBarbearia($barbearia);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'isactive' => 'sometimes|boolean',
        ]);

        $data['barbearia_id'] = $barbearia->id;
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if ($request->expectsJson()) {
            return response()->json($user, 201);
        }

        return redirect()->route('barbearias.dashboard')->with('success', 'Usuário criado com sucesso!');
    }

    public function updateForBarbearia(Request $request, Barbearia $barbearia, User $user)
    {
        $this->authorizeBarbeariaUser($barbearia, $user);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|nullable|string|min:8',
            'isactive' => 'sometimes|boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($request->expectsJson()) {
            return response()->json($user->fresh());
        }

        return redirect()->route('barbearias.dashboard')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroyForBarbearia(Request $request, Barbearia $barbearia, User $user)
    {
        $this->authorizeBarbeariaUser($barbearia, $user);

        $user->delete();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('barbearias.dashboard')->with('success', 'Usuário removido com sucesso!');
    }

    // Atendimentos responsibility
    public function storeAtendimento(Request $request, User $user)
    {
        if (Auth::guard('web')->check()) {
            abort_unless(Auth::guard('web')->id() === $user->id, 403);
        } elseif (Auth::guard('barbearia')->check()) {
            abort_unless(Auth::guard('barbearia')->id() === $user->barbearia_id, 403);
        } else {
            abort(403);
        }

        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'pagamento_id' => 'required|exists:pagamentos,id',
            'valor' => 'required|numeric'
        ]);

        $data['user_id'] = $user->id;
        $data['horario'] = $request->input('horario', now());
        
        $atendimento = Atendimento::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $atendimento->id,
                'user_id' => $atendimento->user_id,
                'service_id' => $atendimento->service_id,
                'pagamento_id' => $atendimento->pagamento_id,
                'valor' => $atendimento->valor,
                'horario' => $atendimento->horario,
            ], 201);
        }

        return redirect()->back()->with('success', 'Atendimento registrado com sucesso!');
    }

    private function authorizeWebUser(User $user): void
    {
        abort_unless(Auth::guard('web')->id() === $user->id, 403);
    }

    private function authorizeBarbearia(Barbearia $barbearia): void
    {
        abort_unless(Auth::guard('barbearia')->id() === $barbearia->id, 403);
    }

    private function authorizeBarbeariaUser(Barbearia $barbearia, User $user): void
    {
        $this->authorizeBarbearia($barbearia);
        abort_unless($user->barbearia_id === $barbearia->id, 403);
    }
}
