<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nguevela · Painel do Salão — {{ $barbearia->name ?? 'Salão' }}</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
<link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=2">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

@php
  $funcionariosPayload = $equipa->map(fn($u) => [
    'id' => $u->id,
    'nome' => $u->name,
    'email' => $u->email,
    'senha' => '••••••',
    'status' => $u->isactive ? 'ativo' : 'inativo',
    'atendimentosMes' => $u->atendimentos_count ?? 0,
  ])->values();

  $servicosPayload = $servicos->map(fn($s) => [
    'id' => $s->id,
    'nome' => $s->name,
    'preco' => (float) $s->price,
  ])->values();

  $atendimentosPayload = $todosAtendimentos->map(fn($a) => [
    'id' => $a->id,
    'funcionarioId' => $a->user_id,
    'funcionarioNome' => $a->user?->name ?? '-',
    'servicoNome' => $a->service?->name ?? '-',
    'pagamento' => $a->pagamento?->name ?? '-',
    'valor' => (float) $a->valor,
    'data' => $a->horario,
  ])->values();
@endphp

<script>
  window.BARBEARIA_DATA = {
    csrfToken: '{{ csrf_token() }}',
    barbeariaId: {{ $barbearia->id ?? 'null' }},
    nomeSalao: @json($barbearia->name ?? 'Salão'),
    pagMeta: {
      'Dinheiro físico':     { label: 'Dinheiro físico',      color: '#10B981' },
      'Multicaixa (TPA)':    { label: 'Multicaixa (TPA)',     color: '#2563EB' },
      'Transferência (IBAN)':{ label: 'Transferência (IBAN)', color: '#3B82F6' },
    },
    funcionarios: @json($funcionariosPayload),
    servicos: @json($servicosPayload),
    atendimentos: @json($atendimentosPayload)
  };
</script>

@vite(['resources/css/dashboard.css', 'resources/js/barbearia-dashboard.js'])
</head>
<body>

<div class="app">

  <!-- ============ SIDEBAR (desktop) ============ -->
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark" id="brandMark">
        {{ strtoupper(substr($barbearia->name ?? 'NB', 0, 2)) }}
      </div>
      <div class="brand-text">
        <div class="brand-name" id="brandName">{{ $barbearia->name ?? 'Salão' }}</div>
        <div class="brand-role">GESTOR DO SALÃO</div>
      </div>
    </div>

    <div class="nav-group-label">Negócio</div>
    <div class="nav-item active" data-view="dashboard" onclick="switchView('dashboard')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
      Dashboard
    </div>
    <div class="nav-item" data-view="caixa" onclick="switchView('caixa')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/></svg>
      Fecho de Caixa
    </div>
    <div class="nav-item" data-view="relatorios" onclick="switchView('relatorios')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6M9 11h6"/></svg>
      Relatórios PDF
    </div>

    <div class="nav-group-label">Gestão</div>
    <div class="nav-item" data-view="equipa" onclick="switchView('equipa')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Equipa
    </div>
    <div class="nav-item" data-view="catalogo" onclick="switchView('catalogo')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v18M18 3v18M3 8h4M3 16h4M17 8h4M17 16h4"/><circle cx="12" cy="12" r="3"/></svg>
      Catálogo de Serviços
    </div>
    <div class="nav-item" data-view="configuracoes" onclick="switchView('configuracoes')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Configurações
    </div>

    <div class="sidebar-footer">
      <div class="admin-chip">
        <div style="display:flex; align-items:center; gap:10px; min-width:0;">
          <div class="admin-avatar">{{ strtoupper(collect(explode(' ', auth()->user()->name ?? 'GS'))->take(2)->map(fn($w) => $w[0])->join('')) }}</div>
          <div style="min-width:0;">
            <div class="admin-name">{{ auth()->user()->name ?? 'Gestor' }}</div>
            <div class="admin-email">{{ auth()->user()->email ?? '' }}</div>
          </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="icon-btn danger" title="Sair da conta" style="flex-shrink:0;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- ============ MAIN ============ -->
  <main class="main">

    <!-- ============ CONTENT AREA ============ -->
    <div class="content-area">

      <!-- ============ PAGE HEADER (NATURAL BLOCK) ============ -->
      <div class="page-header">
        <div>
          <h1 class="page-title" id="pageTitle">Dashboard</h1>
          <div class="page-sub" id="pageSub">Resumo financeiro e operacional do teu salão em tempo real.</div>
        </div>
        <div class="page-header-actions" id="topbarActions">
          <button class="btn btn-ghost" onclick="switchView('relatorios')">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
            Gerar relatório
          </button>
        </div>
      </div>

      <!-- ============ VIEW: DASHBOARD ============ -->
      <section class="view active" id="view-dashboard">

        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">Faturamento hoje</div>
              <div class="kpi-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              </div>
            </div>
            <div class="kpi-value" id="kpiHoje">{{ number_format($faturamentoHoje, 0, ',', ' ') }} Kz</div>
            <div class="kpi-delta" id="kpiHojeAtend">{{ $qtdAtendimentosDia }} atendimento(s)</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">Faturamento na semana</div>
              <div class="kpi-icon" style="background:var(--ok-soft); color:var(--ok);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
              </div>
            </div>
            <div class="kpi-value" id="kpiSemana">{{ number_format($faturamentoSemana, 0, ',', ' ') }} Kz</div>
            <div class="kpi-delta up" id="kpiSemanaAtend">{{ $qtdAtendimentosSemana }} atendimento(s)</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">Faturamento no mês</div>
              <div class="kpi-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              </div>
            </div>
            <div class="kpi-value" id="kpiMes">{{ number_format($faturamentoMes, 0, ',', ' ') }} Kz</div>
            <div class="kpi-delta" id="kpiMesAtend">{{ $qtdAtendimentosMes }} atendimento(s)</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">Atendimentos no mês</div>
              <div class="kpi-icon" style="background:var(--accent-soft); color:var(--accent);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
              </div>
            </div>
            <div class="kpi-value" id="kpiTotalAtend">{{ $qtdAtendimentosMes }}</div>
            <div class="kpi-delta" id="kpiTicketMedio">ticket médio {{ number_format($ticketMedioMes, 0, ',', ' ') }} Kz</div>
          </div>
        </div>

        <div class="dash-grid">
          <div class="card">
            <div class="card-title">Fecho de caixa · hoje</div>
            <div class="card-sub">Divisão dos valores recebidos por método de pagamento</div>
            <div id="cashBreakdown">
              @php
                $cashColors = ['Dinheiro físico' => '#10B981', 'Multicaixa (TPA)' => '#2563EB', 'Transferência (IBAN)' => '#3B82F6'];
                $totalHoje = $faturamentoHoje ?: 1;
              @endphp
              @foreach($fechoCaixaHoje as $item)
                @php
                  $cor = $cashColors[$item['metodo']] ?? '#2563EB';
                  $pct = round(($item['total'] / $totalHoje) * 100);
                @endphp
                <div class="cash-row">
                  <div class="cash-row-top">
                    <div class="cash-row-label"><span class="cash-dot" style="background:{{ $cor }};"></span>{{ $item['metodo'] }}</div>
                    <div class="cash-row-value">{{ number_format($item['total'], 0, ',', ' ') }} Kz</div>
                  </div>
                  <div class="cash-bar-track"><div class="cash-bar-fill" style="width:{{ $faturamentoHoje > 0 ? $pct : 0 }}%; background:{{ $cor }};"></div></div>
                </div>
              @endforeach
            </div>
            <div class="cash-total">
              <div class="cash-total-label">Total do dia</div>
              <div class="cash-total-value">{{ number_format($faturamentoHoje, 0, ',', ' ') }} Kz</div>
            </div>
          </div>

          <div class="card">
            <div class="card-title">Serviços mais populares</div>
            <div class="card-sub">Baseado nos atendimentos do mês corrente</div>
            <div id="serviceRanking">
              @forelse($servicosPopulares as $i => $s)
                <div class="service-rank-row">
                  <div class="service-rank-num">{{ $i + 1 }}</div>
                  <div class="service-rank-info">
                    <div class="service-rank-name">{{ $s['nome'] }}</div>
                    <div class="service-rank-meta">{{ $s['count'] }} atendimento(s)</div>
                  </div>
                  <div class="service-rank-value">{{ number_format($s['total'], 0, ',', ' ') }} Kz</div>
                </div>
              @empty
                <div class="muted">Ainda não há atendimentos este mês.</div>
              @endforelse
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-title">Produtividade da equipa</div>
          <div class="card-sub">Total faturado por cada barbeiro no mês — útil para cálculo de comissões</div>
          <div id="teamProductivity">
            @php $maxTotal = $produtividadeEquipa->max('total') ?: 1; @endphp
            @forelse($produtividadeEquipa as $p)
              @php $pct = round(($p['total'] / $maxTotal) * 100); @endphp
              <div class="prod-row">
                <div class="prod-top">
                  <div class="prod-who">
                    <div class="prod-avatar">{{ strtoupper(collect(explode(' ', $p['nome']))->take(2)->map(fn($w) => $w[0])->join('')) }}</div>
                    <div>
                      <div class="prod-name">{{ $p['nome'] }}</div>
                      <div class="prod-sub">{{ $p['count'] }} atendimento(s)</div>
                    </div>
                  </div>
                  <div class="prod-value">{{ number_format($p['total'], 0, ',', ' ') }} Kz</div>
                </div>
                <div class="prod-bar-track"><div class="prod-bar-fill" style="width:{{ $pct }}%;"></div></div>
              </div>
            @empty
              <div class="muted">Sem dados de produtividade este mês.</div>
            @endforelse
          </div>
        </div>

      </section>

      <!-- ============ VIEW: FECHO DE CAIXA ============ -->
      <section class="view" id="view-caixa">

        <div class="filter-bar">
          <div class="filter-chip active" data-period="dia" onclick="setCaixaPeriod('dia')">Hoje</div>
          <div class="filter-chip" data-period="semana" onclick="setCaixaPeriod('semana')">Esta semana</div>
          <div class="filter-chip" data-period="mes" onclick="setCaixaPeriod('mes')">Este mês</div>
        </div>

        <div class="report-summary-grid">
          <div class="card">
            <div class="kpi-label">Dinheiro físico</div>
            <div class="kpi-value" id="caixaDinheiro" style="font-size:22px; margin-top:8px;">—</div>
          </div>
          <div class="card">
            <div class="kpi-label">Multicaixa (TPA)</div>
            <div class="kpi-value" id="caixaMulticaixa" style="font-size:22px; margin-top:8px;">—</div>
          </div>
          <div class="card">
            <div class="kpi-label">Transferência (IBAN)</div>
            <div class="kpi-value" id="caixaTransferencia" style="font-size:22px; margin-top:8px;">—</div>
          </div>
        </div>

        <div class="panel">
          <div class="table-wrap">
            <table>
              <thead>
                <tr><th>Hora</th><th>Barbeiro</th><th>Serviço</th><th>Pagamento</th><th>Valor</th></tr>
              </thead>
              <tbody id="caixaBody"></tbody>
            </table>
          </div>
          <div id="caixaEmpty" class="empty-state" style="display:none;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            <div class="empty-state-title">Sem atendimentos neste período</div>
            <div>Assim que a equipa registar atendimentos, aparecem aqui.</div>
          </div>
        </div>

      </section>

      <!-- ============ VIEW: RELATÓRIOS PDF ============ -->
      <section class="view" id="view-relatorios">

        <div class="report-filter">
          <div class="report-field">
            <label>PERÍODO</label>
            <select class="field-select" id="repPeriodo" onchange="onReportPeriodChange()">
              <option value="dia">Diário</option>
              <option value="semana">Semanal</option>
              <option value="mes" selected>Mensal</option>
            </select>
          </div>
          <div class="report-field" id="repDiaWrap" style="display:none;">
            <label>DIA</label>
            <input type="date" class="field-input" id="repDia" onchange="renderRelatorio()">
          </div>
          <div class="report-field" id="repSemanaWrap" style="display:none;">
            <label>UMA DATA DA SEMANA</label>
            <input type="date" class="field-input" id="repSemana" onchange="renderRelatorio()">
          </div>
          <div class="report-field" id="repMesWrap">
            <label>MÊS</label>
            <input type="month" class="field-input" id="repMes" onchange="renderRelatorio()">
          </div>
          <div class="report-field" style="min-width:auto;">
            <button class="btn btn-primary" onclick="gerarRelatorioPDF()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
              Exportar PDF
            </button>
          </div>
        </div>

        <div class="report-summary-grid">
          <div class="card"><div class="kpi-label">Faturamento do período</div><div class="kpi-value" id="repFaturamento" style="font-size:22px; margin-top:8px;">—</div></div>
          <div class="card"><div class="kpi-label">Atendimentos</div><div class="kpi-value" id="repAtendimentos" style="font-size:22px; margin-top:8px;">—</div></div>
          <div class="card"><div class="kpi-label">Ticket médio</div><div class="kpi-value" id="repTicket" style="font-size:22px; margin-top:8px;">—</div></div>
        </div>

        <div class="card-sub" style="margin-bottom:10px; padding-left:2px;">Pré-visualização do que será incluído no PDF</div>
        <div class="panel"><div class="table-wrap"><table><thead><tr><th>Método de pagamento</th><th>Valor recebido</th><th>% do total</th></tr></thead><tbody id="repPagamentoBody"></tbody></table></div></div>
        <div style="height:16px;"></div>
        <div class="panel"><div class="table-wrap"><table><thead><tr><th>Barbeiro</th><th>Atendimentos</th><th>Faturado</th></tr></thead><tbody id="repEquipaBody"></tbody></table></div></div>

      </section>

      <!-- ============ VIEW: EQUIPA ============ -->
      <section class="view" id="view-equipa">

        <div class="topbar" style="margin-bottom:16px; padding:0; background:none; border:none;">
          <div>
            <div class="page-sub" style="margin-top:0;">Adiciona a tua equipa e gere as credenciais de acesso de cada barbeiro.</div>
          </div>
          <div class="topbar-actions">
            <div class="search-field">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
              <input id="equipaSearch" type="text" placeholder="Procurar funcionário..." oninput="renderEquipa()">
            </div>
            <button class="btn btn-primary desktop-only" onclick="openFuncionarioModal()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
              Novo funcionário
            </button>
          </div>
        </div>

        <div class="panel">
          <div class="table-wrap">
            <table>
              <thead>
                <tr><th>Funcionário</th><th>Login</th><th>Atendimentos (mês)</th><th>Status</th><th></th></tr>
              </thead>
              <tbody id="equipaBody"></tbody>
            </table>
          </div>
          <div id="equipaEmpty" class="empty-state" style="display:none;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <div class="empty-state-title">Nenhum funcionário encontrado</div>
            <div>Tenta outro termo de pesquisa ou adiciona um novo funcionário.</div>
          </div>
        </div>

      </section>

      <!-- ============ VIEW: CATÁLOGO ============ -->
      <section class="view" id="view-catalogo">

        <div class="topbar" style="margin-bottom:16px; padding:0; background:none; border:none;">
          <div>
            <div class="page-sub" style="margin-top:0;">Define os serviços oferecidos pelo salão e os respetivos preços em Kwanzas.</div>
          </div>
          <div class="topbar-actions">
            <div class="search-field">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
              <input id="catalogoSearch" type="text" placeholder="Procurar serviço..." oninput="renderCatalogo()">
            </div>
            <button class="btn btn-primary desktop-only" onclick="openServicoModal()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
              Novo serviço
            </button>
          </div>
        </div>

        <div class="catalog-grid" id="catalogoGrid"></div>
        <div id="catalogoEmpty" class="empty-state" style="display:none;">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <div class="empty-state-title">Nenhum serviço encontrado</div>
          <div>Tenta outro termo de pesquisa ou adiciona um novo serviço ao catálogo.</div>
        </div>

      </section>

      <!-- ============ VIEW: CONFIGURAÇÕES ============ -->
      <section class="view" id="view-configuracoes">

        <div class="settings-card">
          <div class="settings-card-title">Identidade do salão</div>
          <div class="settings-card-desc">O nome aparece na barra lateral e nos relatórios gerados.</div>
          <div class="field-block">
            <label class="field-label">NOME DO SALÃO</label>
            <input class="field-input" id="s-nome-salao" type="text" value="{{ $barbearia->name ?? '' }}">
          </div>
          <div class="modal-actions" style="margin-top:4px; padding-top:16px;">
            <button class="btn btn-primary" onclick="saveSalonSettings()">Guardar alterações</button>
          </div>
        </div>

        <div class="settings-card">
          <div class="settings-card-title">Perfil do gestor</div>
          <div class="settings-card-desc">Dados de acesso do administrador deste salão.</div>
          <div class="field-block" style="margin-bottom:0;">
            <label class="field-label">NOME</label>
            <input class="field-input" id="s-nome" type="text" value="{{ auth()->user()->name ?? '' }}">
          </div>
          <div class="modal-actions" style="margin-top:18px; padding-top:16px;">
            <button class="btn btn-primary" onclick="saveGestorSettings()">Guardar alterações</button>
          </div>
        </div>

      </section>

    </div>

  </main>
</div>

<!-- ============ MOBILE BOTTOM NAV ============ -->
<nav class="mobile-nav">
  <div class="mobile-nav-inner">
    <div class="mobile-nav-item active" data-view="dashboard" onclick="switchView('dashboard')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
      Painel
    </div>
    <div class="mobile-nav-item" data-view="caixa" onclick="switchView('caixa')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
      Caixa
    </div>
    <div class="mobile-nav-item" data-view="equipa" onclick="switchView('equipa')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      Equipa
    </div>
    <div class="mobile-nav-item" data-view="catalogo" onclick="switchView('catalogo')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v18M18 3v18M3 8h4M3 16h4M17 8h4M17 16h4"/><circle cx="12" cy="12" r="3"/></svg>
      Serviços
    </div>
    <div class="mobile-nav-item" data-view="configuracoes" onclick="switchView('configuracoes')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Config
    </div>
  </div>
</nav>

<button class="mobile-fab" id="mobileFab" onclick="mobileFabAction()">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
</button>

<!-- ============ MODAL: VER FUNCIONÁRIO ============ -->
<div class="modal-overlay" id="viewFuncionarioModal">
  <div class="modal" style="max-width:420px;">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="vf-nome">Detalhes do funcionário</div>
        <div class="modal-desc" style="margin-bottom:0;">Informações do membro da equipa.</div>
      </div>
      <div class="modal-close" onclick="closeModal('viewFuncionarioModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>

    <div class="summary-card" style="margin-top:16px; margin-bottom:16px;">
      <div class="summary-row"><div class="summary-label">Nome Completo</div><div class="summary-value" id="vf-nome-val">—</div></div>
      <div class="summary-row"><div class="summary-label">Email de Login</div><div class="summary-value" id="vf-email-val">—</div></div>
      <div class="summary-row"><div class="summary-label">Atendimentos no Mês</div><div class="summary-value" id="vf-atend-val">—</div></div>
      <div class="summary-row"><div class="summary-label">Estado</div><div class="summary-value" id="vf-status-val">—</div></div>
    </div>

    <div class="modal-actions">
      <button type="button" class="btn btn-ghost" onclick="closeModal('viewFuncionarioModal')">Fechar</button>
      <button type="button" class="btn btn-primary" id="vf-btnEdit">Editar dados</button>
    </div>
  </div>
</div>

<!-- ============ MODAL: FUNCIONÁRIO ============ -->
<div class="modal-overlay" id="funcionarioModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="funcionarioModalTitle">Novo funcionário</div>
      <div class="modal-close" onclick="closeModal('funcionarioModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>
    <div class="modal-desc">Estes dados serão usados pelo barbeiro para iniciar sessão na interface mobile.</div>
    <form onsubmit="return false;">
      <div class="field-block"><label class="field-label">NOME COMPLETO</label><input class="field-input" id="fn-nome" type="text" placeholder="Ex: Carlos Neto"></div>
      <div class="field-block"><label class="field-label">EMAIL DE LOGIN</label><input class="field-input" id="fn-email" type="email" placeholder="carlos@salao.ao"></div>
      
      <div class="field-block" id="fn-senha-atual-wrap" style="display:none;">
        <label class="field-label">PALAVRA-PASSE ATUAL</label>
        <input class="field-input" type="text" value="••••••••" disabled style="background:var(--bg-main);">
      </div>

      <div class="field-block" style="margin-bottom:0;">
        <label class="field-label" id="fn-senha-label">NOVA PALAVRA-PASSE (MÍN. 6 CHARS)</label>
        <div class="field-with-action">
          <input class="field-input" id="fn-senha" type="password" minlength="6" placeholder="Deixa em branco para manter a atual" style="padding-right:70px;">
          <div class="field-inline-actions">
            <button type="button" class="field-inline-action" onclick="togglePasswordVisibility('fn-senha', this)">Mostrar</button>
          </div>
        </div>
      </div>
    </form>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal('funcionarioModal')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveFuncionario()">Guardar</button>
    </div>
  </div>
</div>

<!-- ============ MODAL: SERVIÇO ============ -->
<div class="modal-overlay" id="servicoModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="servicoModalTitle">Novo serviço</div>
      <div class="modal-close" onclick="closeModal('servicoModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>
    <div class="modal-desc">Este serviço passa a estar disponível no fluxo rápido de atendimento dos barbeiros.</div>
    <form onsubmit="return false;">
      <div class="field-block"><label class="field-label">NOME DO SERVIÇO</label><input class="field-input" id="sv-nome" type="text" placeholder="Ex: Corte de Cabelo"></div>
      <div class="field-block" style="margin-bottom:0;"><label class="field-label">PREÇO (KZ)</label><input class="field-input" id="sv-preco" type="number" min="0" step="50" placeholder="Ex: 2500"></div>
    </form>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal('servicoModal')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveServico()">Guardar</button>
    </div>
  </div>
</div>

<!-- ============ MODAL: CONFIRMAÇÃO ============ -->
<div class="modal-overlay" id="confirmModal">
  <div class="modal" style="max-width:400px;">
    <div class="confirm-icon" style="background:var(--danger-soft); color:var(--danger);">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
    </div>
    <div class="modal-title" id="confirmTitle">Remover item</div>
    <div class="modal-desc" id="confirmDesc">Esta ação não pode ser desfeita.</div>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal('confirmModal')">Cancelar</button>
      <button class="btn btn-danger-ghost" id="confirmActionBtn">Remover</button>
    </div>
  </div>
</div>

<!-- ============ TOAST ============ -->
<div class="toast" id="toast">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="toastMsg">Alterações guardadas.</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
