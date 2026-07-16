<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Atendimento;
use App\Models\Barbearia;
use App\Models\Pagamento;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class BarbeariaController extends Controller
{
    public function index()
    {
        $barbearia = Auth::user()?->barbearia;

        if (! $barbearia) {
            // Se o usuário não estiver associado a nenhuma barbearia (ex: super admin)
            $barbearias = Barbearia::with('admin')->get();

            return view('barbearias.index', compact('barbearias'));
        }

        $userIds = $barbearia->users()->pluck('id');

        $hoje = Carbon::today();
        $inicioSemana = Carbon::now()->startOfWeek();
        $inicioMes = Carbon::now()->startOfMonth();

        // Busca todos os atendimentos da barbearia
        $atendimentos = Atendimento::with(['user', 'service', 'pagamento'])
            ->whereIn('user_id', $userIds)
            ->get();

        $atendimentosMes = $atendimentos->where('horario', '>=', $inicioMes);
        $atendimentosSemana = $atendimentos->where('horario', '>=', $inicioSemana);
        $atendimentosHoje = $atendimentos->where('horario', '>=', $hoje);

        $faturamentoHoje = $atendimentosHoje->sum('valor');
        $faturamentoSemana = $atendimentosSemana->sum('valor');
        $faturamentoMes = $atendimentosMes->sum('valor');

        $qtdAtendimentosMes = $atendimentosMes->count();
        $ticketMedioMes = $qtdAtendimentosMes > 0 ? $faturamentoMes / $qtdAtendimentosMes : 0;

        // Fecho de caixa (Hoje) por método
        $pagamentos = Pagamento::all();
        $fechoCaixaHoje = [];
        foreach ($pagamentos as $pagamento) {
            $fechoCaixaHoje[$pagamento->name] = $atendimentosHoje->where('pagamento_id', $pagamento->id)->sum('valor');
        }

        // Serviços mais populares no mês
        $servicosPopulares = $atendimentosMes->groupBy('service_id')->map(function ($atends) {
            $service = $atends->first()->service;

            return [
                'name' => $service ? $service->name : 'Desconhecido',
                'qtd' => $atends->count(),
                'total' => $atends->sum('valor'),
            ];
        })->sortByDesc('qtd')->take(5);

        // Equipa e Produtividade
        $equipa = User::where('barbearia_id', $barbearia->id)
            ->withCount(['atendimentos' => function ($query) use ($inicioMes) {
                $query->where('horario', '>=', $inicioMes);
            }])
            ->withSum(['atendimentos' => function ($query) use ($inicioMes) {
                $query->where('horario', '>=', $inicioMes);
            }], 'valor')
            ->get();

        // Serviços oferecidos
        $servicos = Service::all();

        // Tabela de Fecho de Caixa (Atendimentos de hoje)
        $ultimosAtendimentos = $atendimentosHoje->sortByDesc('horario');

        return view('pages.barbeariaDashboard', compact(
            'barbearia',
            'faturamentoHoje',
            'faturamentoSemana',
            'faturamentoMes',
            'qtdAtendimentosMes',
            'ticketMedioMes',
            'fechoCaixaHoje',
            'servicosPopulares',
            'equipa',
            'servicos',
            'ultimosAtendimentos'
        ));
    }

    public function create()
    {
        $admins = Admin::all();

        return view('barbearias.create', compact('admins'));
    }

    public function store(Request $request)
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
            'isactive' => 'boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        Barbearia::create($data);

        return redirect()->route('barbearias.index')->with('success', 'Barbearia criada com sucesso!');
    }

    public function show(Barbearia $barbearia)
    {
        return view('barbearias.show', compact('barbearia'));
    }

    public function edit(Barbearia $barbearia)
    {
        $admins = Admin::all();

        return view('barbearias.edit', compact('barbearia', 'admins'));
    }

    public function update(Request $request, Barbearia $barbearia)
    {
        $data = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'name' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'plano' => 'required|numeric',
            'gestor' => 'required|string|max:255',
            'email' => 'required|email|unique:barbearias,email,'.$barbearia->id,
            'number' => 'required|string|max:255',
            'isactive' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $barbearia->update($data);

        return redirect()->route('barbearias.index')->with('success', 'Barbearia atualizada com sucesso!');
    }

    public function destroy(Barbearia $barbearia)
    {
        $barbearia->delete();

        return redirect()->route('barbearias.index')->with('success', 'Barbearia removida com sucesso!');
    }
}
