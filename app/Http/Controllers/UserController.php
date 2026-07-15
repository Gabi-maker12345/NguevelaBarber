<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::with('barbearia')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $barbearias = \App\Models\Barbearia::all();
        return view('users.create', compact('barbearias'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'barbearia_id' => 'nullable|exists:barbearias,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'isactive' => 'boolean'
        ]);

        \App\Models\User::create($data);
        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function show(\App\Models\User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(\App\Models\User $user)
    {
        $barbearias = \App\Models\Barbearia::all();
        return view('users.edit', compact('user', 'barbearias'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\User $user)
    {
        $data = $request->validate([
            'barbearia_id' => 'nullable|exists:barbearias,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'isactive' => 'boolean'
        ]);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(\App\Models\User $user)
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
            'valor' => 'required|numeric',
            'horario' => 'required|date'
        ]);

        $data['user_id'] = $user->id;
        \App\Models\Atendimento::create($data);

        return redirect()->back()->with('success', 'Atendimento registrado com sucesso!');
    }
}
