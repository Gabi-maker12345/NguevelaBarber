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
        $barbearia = Auth::guard('barbearia')->user();

        if (! $barbearia) {
            return redirect()->route('admins.index');
        }

        $userIds = User::where('barbearia_id', $barbearia->id)->pluck('id');

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

        $qtdAtendimentosDia = $atendimentosHoje->count();
        $qtdAtendimentosSemana = $atendimentosSemana->count();
        $qtdAtendimentosMes = $atendimentosMes->count();
        $ticketMedioMes = $qtdAtendimentosMes > 0 ? $faturamentoMes / $qtdAtendimentosMes : 0;

        // Fecho de caixa (Hoje) por método
        $pagamentos = Pagamento::all();
        $fechoCaixaHoje = $pagamentos->map(fn ($pagamento) => [
            'metodo' => $pagamento->name,
            'total' => $atendimentosHoje->where('pagamento_id', $pagamento->id)->sum('valor'),
        ])->values();

        // Serviços mais populares no mês
        $servicosPopulares = $atendimentosMes->groupBy('service_id')->map(function ($atends) {
            $service = $atends->first()->service;

            return [
                'nome' => $service ? $service->name : 'Desconhecido',
                'count' => $atends->count(),
                'total' => $atends->sum('valor'),
            ];
        })->sortByDesc('count')->take(5)->values();

        // Equipa e Produtividade
        $equipa = User::where('barbearia_id', $barbearia->id)
            ->withCount(['atendimentos' => function ($query) use ($inicioMes) {
                $query->where('horario', '>=', $inicioMes);
            }])
            ->withSum(['atendimentos' => function ($query) use ($inicioMes) {
                $query->where('horario', '>=', $inicioMes);
            }], 'valor')
            ->get();

        $produtividadeEquipa = $equipa->map(fn ($user) => [
            'nome' => $user->name,
            'count' => $user->atendimentos_count,
            'total' => $user->atendimentos_sum_valor ?? 0,
        ])->sortByDesc('total')->values();

        // Serviços oferecidos
        $servicos = Service::all();

        // Tabela de Fecho de Caixa (Atendimentos de hoje)
        $ultimosAtendimentos = $atendimentosHoje->sortByDesc('horario');
        $todosAtendimentos = $atendimentos
            ->where('horario', '>=', now()->subDays(45))
            ->sortByDesc('horario')
            ->values();

        return view('pages.barbeariaDashboard', compact(
            'barbearia',
            'faturamentoHoje',
            'faturamentoSemana',
            'faturamentoMes',
            'qtdAtendimentosDia',
            'qtdAtendimentosSemana',
            'qtdAtendimentosMes',
            'ticketMedioMes',
            'fechoCaixaHoje',
            'servicosPopulares',
            'produtividadeEquipa',
            'equipa',
            'servicos',
            'ultimosAtendimentos',
            'todosAtendimentos'
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

        return redirect()->route('admins.index')->with('success', 'Barbearia criada com sucesso!');
    }

    public function show(Barbearia $barbearia)
    {
        if (Auth::guard('barbearia')->check()) {
            abort_unless(Auth::guard('barbearia')->id() === $barbearia->id, 403);
        }

        return view('barbearias.show', compact('barbearia'));
    }

    public function edit(Barbearia $barbearia)
    {
        if (Auth::guard('barbearia')->check()) {
            abort_unless(Auth::guard('barbearia')->id() === $barbearia->id, 403);
        }

        $admins = Admin::all();
        return view('barbearias.edit', compact('barbearia', 'admins'));
    }

    public function update(Request $request, Barbearia $barbearia)
    {
        $isAdmin = Auth::guard('admin')->check();
        $isBarbearia = Auth::guard('barbearia')->check();

        if ($isBarbearia) {
            abort_unless(Auth::guard('barbearia')->id() === $barbearia->id, 403);
        }

        $rules = $isAdmin ? [
            'admin_id' => 'sometimes|required|exists:admins,id',
            'name' => 'sometimes|required|string|max:255',
            'municipio' => 'sometimes|required|string|max:255',
            'plano' => 'sometimes|required|numeric',
            'gestor' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:barbearias,email,'.$barbearia->id,
            'number' => 'sometimes|required|string|max:255',
            'isactive' => 'sometimes|boolean',
        ] : [
            'name' => 'sometimes|required|string|max:255',
            'gestor' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:barbearias,email,'.$barbearia->id,
            'number' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|nullable|string|min:8',
        ];

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $barbearia->update($data);

        if ($request->expectsJson()) {
            return response()->json($barbearia->fresh());
        }

        return redirect()
            ->route($isAdmin ? 'admins.index' : 'barbearias.dashboard')
            ->with('success', 'Barbearia atualizada com sucesso!');
    }

    public function destroy(Barbearia $barbearia)
    {
        $barbearia->delete();
        return redirect()->route('admins.index')->with('success', 'Barbearia removida com sucesso!');
    }
}
