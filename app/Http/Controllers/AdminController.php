<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Barbearia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Carrega todas as barbearias com a contagem de usuários (equipa)
        $barbearias = Barbearia::withCount('users')->get();

        // Indicadores do topo
        $totalSaloes = $barbearias->count();
        $saloesAtivos = $barbearias->where('isactive', true)->count();
        
        // Soma apenas dos ativos para receita mensal
        $receitaMensal = $barbearias->where('isactive', true)->sum('plano');

        $ticketMedio = $saloesAtivos > 0 ? $receitaMensal / $saloesAtivos : 0;

        // Salões inativos / suspensos / em atraso
        $saloesEmAtraso = $barbearias->where('isactive', false)->count();
        $valorEmRisco = $barbearias->where('isactive', false)->sum('plano');

        // Salões a expirar em até 5 dias
        $saloesAExpirar = $barbearias->filter(function ($barbearia) {
            // Verifica se a propriedade existe para evitar erros
            return isset($barbearia->days_until_expiration) 
                   && $barbearia->days_until_expiration <= 5 
                   && $barbearia->days_until_expiration >= 0;
        })->count();

        return view('pages.adminDashboard', compact(
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8',
            'isactive' => 'boolean',
        ]);

        // Garante hash da senha
        $data['password'] = Hash::make($data['password']);

        Admin::create($data);

        return redirect()->route('admins.index')->with('success', 'Admin criado com sucesso!');
    }

    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'isactive' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admins.index')->with('success', 'Admin atualizado com sucesso!');
    }

    public function destroy(Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        if ($currentAdmin && $currentAdmin->id === $admin->id) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin removido com sucesso!');
    }
}