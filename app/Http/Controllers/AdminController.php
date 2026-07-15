<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Carrega todas as barbearias com a contagem de usuários (equipa)
        $barbearias = \App\Models\Barbearia::withCount('users')->get();
        
        // Indicadores do topo
        $totalSaloes = $barbearias->count();
        $saloesAtivos = $barbearias->where('isactive', true)->count();
        $receitaMensal = $barbearias->where('isactive', true)->sum('plano');
        
        $ticketMedio = $saloesAtivos > 0 ? $receitaMensal / $saloesAtivos : 0;
        
        // Salões inativos / suspensos / em atraso
        $saloesEmAtraso = $barbearias->where('isactive', false)->count();
        $valorEmRisco = $barbearias->where('isactive', false)->sum('plano');
        
        // Salões a expirar em até 5 dias
        $saloesAExpirar = $barbearias->filter(function ($barbearia) {
            return $barbearia->days_until_expiration <= 5 && $barbearia->days_until_expiration >= 0;
        })->count(); 
        
        return view('admins.index', compact(
            'barbearias',
            'totalSaloes',
            'saloesAtivos',
            'receitaMensal',
            'saloesAExpirar',
            'ticketMedio',
            'saloesEmAtraso',
            'valorEmRisco'
        ));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8',
            'isactive' => 'boolean'
        ]);
        
        \App\Models\Admin::create($data);
        return redirect()->route('admins.index')->with('success', 'Admin criado com sucesso!');
    }

    public function show(\App\Models\Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    public function edit(\App\Models\Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Admin $admin)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'isactive' => 'boolean'
        ]);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $admin->update($data);
        return redirect()->route('admins.index')->with('success', 'Admin atualizado com sucesso!');
    }

    public function destroy(\App\Models\Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admins.index')->with('success', 'Admin removido com sucesso!');
    }
}
