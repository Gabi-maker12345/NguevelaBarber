<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarbeariaController extends Controller
{
    public function index()
    {
        $barbearias = \App\Models\Barbearia::with('admin')->get();
        return view('barbearias.index', compact('barbearias'));
    }

    public function create()
    {
        $admins = \App\Models\Admin::all();
        return view('barbearias.create', compact('admins'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'name' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'plano' => 'required|numeric',
            'gestor' => 'required|string|max:255',
            'email' => 'required|email|unique:barbearias,email',
            'number' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'isactive' => 'boolean'
        ]);

        \App\Models\Barbearia::create($data);
        return redirect()->route('barbearias.index')->with('success', 'Barbearia criada com sucesso!');
    }

    public function show(\App\Models\Barbearia $barbearia)
    {
        return view('barbearias.show', compact('barbearia'));
    }

    public function edit(\App\Models\Barbearia $barbearia)
    {
        $admins = \App\Models\Admin::all();
        return view('barbearias.edit', compact('barbearia', 'admins'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Barbearia $barbearia)
    {
        $data = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'name' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'plano' => 'required|numeric',
            'gestor' => 'required|string|max:255',
            'email' => 'required|email|unique:barbearias,email,' . $barbearia->id,
            'number' => 'required|string|max:255',
            'isactive' => 'boolean'
        ]);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $barbearia->update($data);
        return redirect()->route('barbearias.index')->with('success', 'Barbearia atualizada com sucesso!');
    }

    public function destroy(\App\Models\Barbearia $barbearia)
    {
        $barbearia->delete();
        return redirect()->route('barbearias.index')->with('success', 'Barbearia removida com sucesso!');
    }
}
