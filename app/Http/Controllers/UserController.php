<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Barbearia;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('barbearia')->get();

        return view('users.index', compact('users'));
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
    public function storeAtendimento(Request $request, User $user)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'pagamento_id' => 'required|exists:pagamentos,id',
            'valor' => 'required|numeric',
            'horario' => 'required|date',
        ]);

        $data['user_id'] = $user->id;
        Atendimento::create($data);

        return redirect()->back()->with('success', 'Atendimento registrado com sucesso!');
    }
}
