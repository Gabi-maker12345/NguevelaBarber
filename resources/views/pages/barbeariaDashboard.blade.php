<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nguevela · Painel do Salão — {{ $barbearia->name ?? 'Salão' }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@vite(['resources/css/dashboard.css'])
<style>
  /* ===== ESTILOS ESPECÍFICOS DA BARBEARIA DASHBOARD ===== */
  .dash-grid{ display:grid; grid-template-columns:1.1fr 1fr; gap:16px; margin-bottom:16px; }
  .card{ background:var(--bg-panel); border:1px solid var(--line); border-radius:var(--radius); padding:20px 22px; }
  .card-title{ font-family:'Oswald',sans-serif; font-weight:600; font-size:15.5px; margin-bottom:2px; }
  .card-sub{ font-size:12px; color:var(--text-faint); margin-bottom:18px; }

  /* fecho de caixa */
  .cash-row{ margin-bottom:16px; }
  .cash-row:last-child{ margin-bottom:0; }
  .cash-row-top{ display:flex; align-items:center; justify-content:space-between; font-size:13px; margin-bottom:7px; }
  .cash-row-label{ display:flex; align-items:center; gap:8px; font-weight:500; }
  .cash-dot{ width:9px; height:9px; border-radius:50%; flex-shrink:0; }
  .cash-row-value{ font-weight:600; }
  .cash-bar-track{ height:8px; border-radius:6px; background:var(--field-bg); overflow:hidden; }
  .cash-bar-fill{ height:100%; border-radius:6px; transition:width .3s ease; }
  .cash-total{ display:flex; align-items:center; justify-content:space-between; margin-top:18px; padding-top:16px; border-top:1px solid var(--line); }
  .cash-total-label{ font-size:13px; color:var(--text-secondary); }
  .cash-total-value{ font-family:'Oswald',sans-serif; font-weight:600; font-size:19px; color:var(--gold); }

  /* serviços populares */
  .service-rank-row{ display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--line); }
  .service-rank-row:last-child{ border-bottom:none; padding-bottom:0; }
  .service-rank-num{ width:22px; height:22px; border-radius:6px; background:var(--gold-soft); color:var(--gold); display:flex; align-items:center; justify-content:center; font-size:11.5px; font-weight:700; flex-shrink:0; }
  .service-rank-info{ flex:1; min-width:0; }
  .service-rank-name{ font-size:13.5px; font-weight:500; }
  .service-rank-meta{ font-size:11.5px; color:var(--text-faint); margin-top:1px; }
  .service-rank-value{ font-size:13px; font-weight:600; color:var(--gold); white-space:nowrap; }

  /* produtividade da equipa */
  .prod-row{ margin-bottom:14px; }
  .prod-row:last-child{ margin-bottom:0; }
  .prod-top{ display:flex; align-items:center; justify-content:space-between; margin-bottom:7px; }
  .prod-who{ display:flex; align-items:center; gap:10px; }
  .prod-avatar{ width:28px; height:28px; border-radius:50%; background:var(--bg-elevated); border:1px solid var(--field-border); display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:var(--gold); flex-shrink:0; }
  .prod-name{ font-size:13px; font-weight:600; }
  .prod-sub{ font-size:11px; color:var(--text-faint); }
  .prod-value{ font-size:13px; font-weight:600; }
  .prod-bar-track{ height:6px; border-radius:6px; background:var(--field-bg); overflow:hidden; }
  .prod-bar-fill{ height:100%; border-radius:6px; background:linear-gradient(90deg,var(--gold),#8a6d1f); }

  /* catálogo */
  .catalog-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
  .catalog-card{ background:var(--bg-panel); border:1px solid var(--line); border-radius:var(--radius); padding:18px; display:flex; flex-direction:column; gap:10px; }
  .catalog-card-top{ display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
  .catalog-name{ font-weight:600; font-size:14.5px; }
  .catalog-price{ font-family:'Oswald',sans-serif; font-weight:600; font-size:20px; color:var(--gold); }
  .catalog-actions{ display:flex; gap:8px; margin-top:auto; }
  .catalog-actions .btn{ flex:1; justify-content:center; }

  /* relatórios */
  .report-filter{ display:flex; align-items:flex-end; gap:14px; flex-wrap:wrap; background:var(--bg-panel); border:1px solid var(--line); border-radius:var(--radius); padding:20px 22px; margin-bottom:20px; }
  .report-field{ display:flex; flex-direction:column; gap:6px; min-width:160px; }
  .report-field label{ font-size:11.5px; color:var(--text-secondary); font-weight:500; }
  .report-summary-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }

  /* configurações específicas */
  .logo-upload-row{ display:flex; align-items:center; gap:16px; margin-bottom:18px; }
  .logo-preview{ width:64px; height:64px; border-radius:12px; background:var(--field-bg); border:1px solid var(--field-border); display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; font-family:'Oswald',sans-serif; font-weight:700; font-size:18px; color:var(--gold); }
  .logo-preview img{ width:100%; height:100%; object-fit:cover; }

  /* funcionário wizard */
  .funcionario-row{ display:flex; flex-direction:column; gap:8px; background:var(--bg-elevated); border:1px solid var(--field-border); border-radius:8px; padding:10px; }
  .funcionario-row-top{ display:grid; grid-template-columns:1fr 1fr; gap:8px; }
  .funcionario-row-bottom{ display:flex; align-items:center; gap:8px; }
  .funcionario-row-bottom .field-with-action{ flex:1; }
  .funcionario-row .field-input{ padding:8px 10px; font-size:13px; }
  .funcionario-remove{ width:34px; height:34px; flex-shrink:0; display:flex; align-items:center; justify-content:center; border-radius:7px; background:transparent; border:1px solid var(--field-border); color:var(--text-faint); cursor:pointer; }
  .funcionario-remove:hover{ color:var(--danger); border-color:var(--danger); }
  .funcionario-list{ display:flex; flex-direction:column; gap:10px; margin-bottom:14px; }
  .funcionario-empty{ text-align:center; padding:22px 12px; color:var(--text-faint); font-size:12.5px; border:1px dashed var(--field-border); border-radius:8px; margin-bottom:14px; }
  .add-funcionario-btn{ display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:11px; border-radius:8px; border:1px dashed var(--field-border); background:none; color:var(--text-secondary); font-size:13px; font-weight:600; cursor:pointer; transition:all .15s ease; }
  .add-funcionario-btn:hover{ border-color:var(--gold); color:var(--gold); }

  @media(max-width:1000px){ .dash-grid{ grid-template-columns:1fr; } .catalog-grid{ grid-template-columns:repeat(2,1fr); } .report-summary-grid{ grid-template-columns:1fr 1fr; } }
  @media(max-width:768px){
    .dash-grid{ gap:10px; } .card{ padding:16px; }
    .report-summary-grid{ grid-template-columns:1fr; gap:10px; } .report-filter{ padding:16px; } .report-field{ min-width:0; flex:1 1 45%; }
    .logo-upload-row{ flex-wrap:wrap; } .catalog-grid{ grid-template-columns:1fr; }
    .funcionario-row-top{ grid-template-columns:1fr; }
  }
</style>
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
        <div class="admin-avatar">{{ strtoupper(collect(explode(' ', auth()->user()->name ?? 'GS'))->take(2)->map(fn($w) => $w[0])->join('')) }}</div>
        <div>
          <div class="admin-name">{{ auth()->user()->name ?? 'Gestor' }}</div>
          <div class="admin-email">{{ auth()->user()->email ?? '' }}</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ============ MAIN ============ -->
  <main class="main">

    <div class="topbar">
      <div>
        <div class="page-title" id="pageTitle">Dashboard</div>
        <div class="page-sub" id="pageSub">Resumo financeiro e operacional do teu salão em tempo real.</div>
      </div>
      <div class="topbar-actions" id="topbarActions">
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
            <div class="kpi-icon" style="background:var(--gold-soft); color:var(--gold);">
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
            <div class="kpi-icon" style="background:var(--info-soft); color:var(--info);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
          </div>
          <div class="kpi-value" id="kpiMes">{{ number_format($faturamentoMes, 0, ',', ' ') }} Kz</div>
          <div class="kpi-delta" id="kpiMesAtend">{{ $qtdAtendimentosMes }} atendimento(s)</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-top">
            <div class="kpi-label">Atendimentos no mês</div>
            <div class="kpi-icon" style="background:var(--warn-soft); color:var(--warn);">
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
              $cashColors = ['Dinheiro físico' => '#4CAF6D', 'Multicaixa (TPA)' => '#D4AF37', 'Transferência (IBAN)' => '#4C93AF'];
              $totalHoje = $faturamentoHoje ?: 1;
            @endphp
            @foreach($fechoCaixaHoje as $item)
              @php
                $cor = $cashColors[$item['metodo']] ?? '#D4AF37';
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
          <button class="btn btn-gold" onclick="gerarRelatorioPDF()">
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

      <div class="topbar" style="margin-bottom:16px;">
        <div>
          <div class="page-sub" style="margin-top:0;">Adiciona a tua equipa e gere as credenciais de acesso de cada barbeiro.</div>
        </div>
        <div class="topbar-actions">
          <div class="search-field">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input id="equipaSearch" type="text" placeholder="Procurar funcionário..." oninput="renderEquipa()">
          </div>
          <button class="btn btn-gold desktop-only" onclick="openFuncionarioModal()">
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

      <div class="topbar" style="margin-bottom:16px;">
        <div>
          <div class="page-sub" style="margin-top:0;">Define os serviços oferecidos pelo salão e os respetivos preços em Kwanzas.</div>
        </div>
        <div class="topbar-actions">
          <div class="search-field">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input id="catalogoSearch" type="text" placeholder="Procurar serviço..." oninput="renderCatalogo()">
          </div>
          <button class="btn btn-gold desktop-only" onclick="openServicoModal()">
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
          <button class="btn btn-gold" onclick="saveSalonSettings()">Guardar alterações</button>
        </div>
      </div>

      <div class="settings-card">
        <div class="settings-card-title">Perfil do gestor</div>
        <div class="settings-card-desc">Dados de acesso do administrador deste salão.</div>
        <div class="field-block">
          <label class="field-label">NOME</label>
          <input class="field-input" id="s-nome" type="text" value="{{ auth()->user()->name ?? '' }}">
        </div>
        <div class="field-block">
          <label class="field-label">EMAIL</label>
          <input class="field-input" id="s-email" type="email" value="{{ auth()->user()->email ?? '' }}">
        </div>
        <div class="field-block" style="margin-bottom:0;">
          <label class="field-label">NOVA PALAVRA-PASSE</label>
          <input class="field-input" id="s-senha" type="password" placeholder="Deixa em branco para manter a atual">
        </div>
        <div class="modal-actions" style="margin-top:18px; padding-top:16px;">
          <button class="btn btn-gold" onclick="saveGestorSettings()">Guardar alterações</button>
        </div>
      </div>

      <div class="settings-card">
        <div class="settings-card-title">Notificações</div>
        <div class="settings-card-desc">Escolhe quando queres ser avisado sobre a atividade do salão.</div>
        <div class="toggle-row">
          <div>
            <div class="toggle-row-label">Resumo diário de fecho de caixa</div>
            <div class="toggle-row-sub">Recebe o total faturado no fim de cada dia</div>
          </div>
          <label class="switch"><input type="checkbox" checked><span class="switch-track"></span></label>
        </div>
        <div class="toggle-row">
          <div>
            <div class="toggle-row-label">Mensalidade da plataforma a expirar</div>
            <div class="toggle-row-sub">Avisar quando faltarem 5 dias para o fim da subscrição</div>
          </div>
          <label class="switch"><input type="checkbox" checked><span class="switch-track"></span></label>
        </div>
      </div>

      <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger-ghost" style="max-width:240px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
          Sair da conta
        </button>
      </form>

    </section>

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
      <div class="field-block" style="margin-bottom:0;">
        <label class="field-label">PALAVRA-PASSE</label>
        <div class="field-with-action">
          <input class="field-input" id="fn-senha" type="text" placeholder="Palavra-passe de acesso" style="padding-right:70px;">
          <div class="field-inline-actions">
            <button type="button" class="field-inline-action" onclick="document.getElementById('fn-senha').value = gerarSenha()">Gerar</button>
          </div>
        </div>
      </div>
    </form>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal('funcionarioModal')">Cancelar</button>
      <button class="btn btn-gold" onclick="saveFuncionario()">Guardar</button>
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
      <button class="btn btn-gold" onclick="saveServico()">Guardar</button>
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
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="toastMsg">Alterações guardadas.</span>
</div>

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
  // ============================================================
  // DADOS DO BACKEND (injetados pelo Blade)
  // ============================================================
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const BARBEARIA_ID = {{ $barbearia->id ?? 'null' }};
  const NOME_SALAO = @json($barbearia->name ?? 'Salão');

  const PAG_META = {
    'Dinheiro físico':  { label: 'Dinheiro físico',   color: '#4CAF6D' },
    'Multicaixa (TPA)': { label: 'Multicaixa (TPA)',   color: '#D4AF37' },
    'Transferência (IBAN)': { label: 'Transferência (IBAN)', color: '#4C93AF' },
  };

  // Arrays injetados do controller
  let funcionarios = @json($funcionariosPayload);
  let servicos = @json($servicosPayload);
  let atendimentos = @json($atendimentosPayload);

  let funcSeq = funcionarios.length;
  let servSeq = servicos.length;

  // ============================================================
  // HELPERS
  // ============================================================
  function moneyNumber(v){
    const n = Number(v);
    return Number.isFinite(n) ? n : 0;
  }
  function kz(v){ return Math.round(moneyNumber(v)).toLocaleString('pt-AO') + ' Kz'; }
  function initials(nome){ return nome.split(' ').filter(Boolean).slice(0,2).map(p => p[0].toUpperCase()).join(''); }
  function gerarSenha(){ return 'Ng-' + Math.floor(1000 + Math.random()*9000); }
  function isValidEmail(e){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e); }
  function paymentKey(nome){
    if(nome === 'TPA' || nome === 'Multicaixa (TPA)') return 'Multicaixa (TPA)';
    if(nome === 'Transferência' || nome === 'Transferência (IBAN)') return 'Transferência (IBAN)';
    return nome || '-';
  }
  function paymentLabel(nome){
    const key = paymentKey(nome);
    return PAG_META[key]?.label || key;
  }

  function isSameDay(a, b){ const ad = new Date(a), bd = new Date(b); return ad.getFullYear()===bd.getFullYear() && ad.getMonth()===bd.getMonth() && ad.getDate()===bd.getDate(); }
  function startOfWeek(ref){ const d = new Date(ref); const day = d.getDay(); const diff = (day===0 ? -6 : 1) - day; d.setDate(d.getDate()+diff); d.setHours(0,0,0,0); return d; }
  function inRange(data, ini, fim){ const d = new Date(data); return d >= ini && d <= fim; }

  function filterByPeriod(period, refDate){
    const ref = refDate || new Date();
    if(period === 'dia') return atendimentos.filter(a => isSameDay(a.data, ref));
    if(period === 'semana'){
      const ini = startOfWeek(ref);
      const fim = new Date(ini); fim.setDate(ini.getDate()+6); fim.setHours(23,59,59,999);
      return atendimentos.filter(a => inRange(a.data, ini, fim));
    }
    const ini = new Date(ref.getFullYear(), ref.getMonth(), 1);
    const fim = new Date(ref.getFullYear(), ref.getMonth()+1, 0, 23,59,59,999);
    return atendimentos.filter(a => inRange(a.data, ini, fim));
  }

  function sumValor(lista){ return lista.reduce((s,a) => s + moneyNumber(a.valor), 0); }
  function funcById(id){ return funcionarios.find(f => f.id === id); }
  function servById(id){ return servicos.find(s => s.id === id); }

  // ============================================================
  // NAVEGAÇÃO
  // ============================================================
  const pageMeta = {
    dashboard: { title: 'Dashboard', sub: 'Resumo financeiro e operacional do teu salão em tempo real.' },
    caixa: { title: 'Fecho de Caixa', sub: 'Consulta os valores recebidos por método de pagamento em cada período.' },
    relatorios: { title: 'Relatórios PDF', sub: 'Exporta o raio-X financeiro do salão para partilhar ou arquivar.' },
    equipa: { title: 'Equipa', sub: 'Gere os barbeiros do salão e as suas credenciais de acesso.' },
    catalogo: { title: 'Catálogo de Serviços', sub: 'Define os serviços e preços disponíveis para a equipa.' },
    configuracoes: { title: 'Configurações', sub: 'Identidade visual do salão e dados da tua conta de gestor.' }
  };

  function switchView(view){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    document.getElementById('view-' + view).classList.add('active');
    document.querySelectorAll('.nav-item').forEach(n => n.classList.toggle('active', n.dataset.view === view));
    document.querySelectorAll('.mobile-nav-item').forEach(n => n.classList.toggle('active', n.dataset.view === view));
    document.getElementById('pageTitle').textContent = pageMeta[view].title;
    document.getElementById('pageSub').textContent = pageMeta[view].sub;
    const topbarActions = document.getElementById('topbarActions');
    topbarActions.innerHTML = '';
    const fab = document.getElementById('mobileFab');
    fab.classList.remove('show');
    if(view === 'dashboard' || view === 'caixa'){
      topbarActions.innerHTML = `<button class="btn btn-ghost" onclick="switchView('relatorios')"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>Gerar relatório</button>`;
    } else if(view === 'equipa'){
      topbarActions.innerHTML = `<button class="btn btn-gold" onclick="openFuncionarioModal()"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>Novo funcionário</button>`;
      fab.classList.add('show');
    } else if(view === 'catalogo'){
      topbarActions.innerHTML = `<button class="btn btn-gold" onclick="openServicoModal()"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>Novo serviço</button>`;
      fab.classList.add('show');
    }
    if(view === 'caixa') renderCaixa();
    if(view === 'relatorios') renderRelatorio();
    if(view === 'equipa') renderEquipa();
    if(view === 'catalogo') renderCatalogo();
  }

  function mobileFabAction(){
    const active = document.querySelector('.mobile-nav-item.active');
    if(!active) return;
    if(active.dataset.view === 'equipa') openFuncionarioModal();
    else if(active.dataset.view === 'catalogo') openServicoModal();
  }

  // ============================================================
  // FECHO DE CAIXA
  // ============================================================
  let caixaPeriod = 'dia';
  function setCaixaPeriod(p){
    caixaPeriod = p;
    document.querySelectorAll('#view-caixa .filter-chip').forEach(c => c.classList.toggle('active', c.dataset.period === p));
    renderCaixa();
  }

  function renderCaixa(){
    const lista = filterByPeriod(caixaPeriod, new Date());
    const metodos = ['Dinheiro físico', 'Multicaixa (TPA)', 'Transferência (IBAN)'];
    const ids = ['caixaDinheiro', 'caixaMulticaixa', 'caixaTransferencia'];
    metodos.forEach((m, i) => {
      document.getElementById(ids[i]).textContent = kz(sumValor(lista.filter(a => paymentKey(a.pagamento) === m)));
    });
    const body = document.getElementById('caixaBody');
    body.innerHTML = '';
    const ordenada = [...lista].sort((a,b) => new Date(b.data) - new Date(a.data));
    ordenada.forEach(a => {
      const d = new Date(a.data);
      const hora = d.toLocaleTimeString('pt-AO', { hour:'2-digit', minute:'2-digit' });
      const diaLabel = caixaPeriod === 'dia' ? hora : `${d.toLocaleDateString('pt-AO')} ${hora}`;
      const tr = document.createElement('tr');
      tr.innerHTML = `<td data-label="Hora">${diaLabel}</td><td data-label="Barbeiro">${a.funcionarioNome}</td><td data-label="Serviço">${a.servicoNome}</td><td data-label="Pagamento">${paymentLabel(a.pagamento)}</td><td data-label="Valor"><span class="price-tag">${kz(a.valor)}</span></td>`;
      body.appendChild(tr);
    });
    document.getElementById('caixaEmpty').style.display = lista.length ? 'none' : 'block';
  }

  // ============================================================
  // RELATÓRIOS PDF
  // ============================================================
  function onReportPeriodChange(){
    const p = document.getElementById('repPeriodo').value;
    document.getElementById('repDiaWrap').style.display = p==='dia' ? '' : 'none';
    document.getElementById('repSemanaWrap').style.display = p==='semana' ? '' : 'none';
    document.getElementById('repMesWrap').style.display = p==='mes' ? '' : 'none';
    renderRelatorio();
  }

  function getRelatorioData(){
    const periodo = document.getElementById('repPeriodo').value;
    let ref = new Date();
    if(periodo==='dia'){ const v=document.getElementById('repDia').value; if(v){ const[y,m,d]=v.split('-').map(Number); ref=new Date(y,m-1,d,12); } }
    else if(periodo==='semana'){ const v=document.getElementById('repSemana').value; if(v){ const[y,m,d]=v.split('-').map(Number); ref=new Date(y,m-1,d,12); } }
    else{ const v=document.getElementById('repMes').value; if(v){ const[y,m]=v.split('-').map(Number); ref=new Date(y,m-1,15); } }
    return { periodo, ref, lista: filterByPeriod(periodo, ref) };
  }

  function renderRelatorio(){
    const { lista } = getRelatorioData();
    document.getElementById('repFaturamento').textContent = kz(sumValor(lista));
    document.getElementById('repAtendimentos').textContent = lista.length;
    document.getElementById('repTicket').textContent = kz(lista.length ? sumValor(lista)/lista.length : 0);

    const pagBody = document.getElementById('repPagamentoBody');
    pagBody.innerHTML = '';
    const total = sumValor(lista) || 1;
    Object.keys(PAG_META).forEach(key => {
      const valor = sumValor(lista.filter(a => paymentKey(a.pagamento) === key));
      const pct = Math.round((valor/total)*100);
      const tr = document.createElement('tr');
      tr.innerHTML = `<td data-label="Método">${PAG_META[key].label}</td><td data-label="Valor">${kz(valor)}</td><td data-label="%">${sumValor(lista) ? pct : 0}%</td>`;
      pagBody.appendChild(tr);
    });

    const eqBody = document.getElementById('repEquipaBody');
    eqBody.innerHTML = '';
    const porFunc = {};
    lista.forEach(a => {
      if(!porFunc[a.funcionarioId]) porFunc[a.funcionarioId] = { nome: a.funcionarioNome, count:0, total:0 };
      porFunc[a.funcionarioId].count++;
      porFunc[a.funcionarioId].total += moneyNumber(a.valor);
    });
    const eqList = Object.values(porFunc).sort((a,b) => b.total - a.total);
    if(eqList.length===0){
      eqBody.innerHTML = '<tr><td colspan="3" class="muted" style="padding:18px;">Sem atendimentos neste período.</td></tr>';
    } else {
      eqList.forEach(r => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td data-label="Barbeiro">${r.nome}</td><td data-label="Atendimentos">${r.count}</td><td data-label="Faturado"><span class="price-tag">${kz(r.total)}</span></td>`;
        eqBody.appendChild(tr);
      });
    }
  }

  function gerarRelatorioPDF(){
    const { periodo, lista } = getRelatorioData();
    if(typeof window.jspdf === 'undefined'){ showToast('Não foi possível carregar o gerador de PDF.'); return; }
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const periodoLabel = periodo==='dia' ? 'Diário' : periodo==='semana' ? 'Semanal' : 'Mensal';
    doc.setFont('helvetica','bold'); doc.setFontSize(16); doc.text(NOME_SALAO, 14, 20);
    doc.setFontSize(11); doc.setFont('helvetica','normal');
    doc.text(`Relatório financeiro · ${periodoLabel}`, 14, 27);
    doc.text(`Gerado em ${new Date().toLocaleDateString('pt-AO')} às ${new Date().toLocaleTimeString('pt-AO', {hour:'2-digit',minute:'2-digit'})}`, 14, 33);
    doc.setDrawColor(212,175,55); doc.line(14,37,196,37);
    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.text('Resumo', 14, 46);
    doc.setFont('helvetica','normal'); doc.setFontSize(11);
    doc.text(`Faturamento total: ${kz(sumValor(lista))}`, 14, 54);
    doc.text(`Atendimentos: ${lista.length}`, 14, 61);
    doc.text(`Ticket médio: ${kz(lista.length ? sumValor(lista)/lista.length : 0)}`, 14, 68);
    let y = 80;
    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.text('Métodos de pagamento', 14, y); y+=8;
    doc.setFont('helvetica','normal'); doc.setFontSize(11);
    const total = sumValor(lista) || 1;
    Object.keys(PAG_META).forEach(key => {
      const valor = sumValor(lista.filter(a => paymentKey(a.pagamento) === key));
      doc.text(`${PAG_META[key].label}: ${kz(valor)} (${Math.round((valor/total)*100)}%)`, 14, y); y+=7;
    });
    y+=8; doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.text('Produtividade da equipa', 14, y); y+=8;
    doc.setFont('helvetica','normal'); doc.setFontSize(11);
    const porFunc = {};
    lista.forEach(a => { if(!porFunc[a.funcionarioId]) porFunc[a.funcionarioId]={nome:a.funcionarioNome,count:0,total:0}; porFunc[a.funcionarioId].count++; porFunc[a.funcionarioId].total+=moneyNumber(a.valor); });
    Object.values(porFunc).sort((a,b) => b.total-a.total).forEach(r => { if(y>275){ doc.addPage(); y=20; } doc.text(`${r.nome} — ${r.count} atendimento(s) — ${kz(r.total)}`, 14, y); y+=7; });
    doc.save(`relatorio-${periodo}-${new Date().toISOString().slice(0,10)}.pdf`);
    showToast('Relatório PDF gerado com sucesso.');
  }

  // ============================================================
  // EQUIPA
  // ============================================================
  function renderEquipa(){
    const body = document.getElementById('equipaBody');
    body.innerHTML = '';
    const termo = (document.getElementById('equipaSearch').value || '').trim().toLowerCase();
    const lista = termo ? funcionarios.filter(f => f.nome.toLowerCase().includes(termo) || f.email.toLowerCase().includes(termo)) : funcionarios;
    document.getElementById('equipaEmpty').style.display = lista.length ? 'none' : 'block';
    if(!lista.length) return;
    lista.forEach(f => {
      const count = f.atendimentosMes || 0;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td data-label="Funcionário" class="cell-person">
          <div class="person-cell">
            <div class="person-avatar">${initials(f.nome)}</div>
            <div><div class="person-name">${f.nome}</div></div>
          </div>
        </td>
        <td data-label="Login">${f.email}</td>
        <td data-label="Atendimentos">${count}</td>
        <td data-label="Status"><span class="status-badge ${f.status}"><span class="status-dot"></span>${f.status === 'ativo' ? 'Ativo' : 'Inativo'}</span></td>
        <td data-label="" class="cell-actions">
          <div class="row-actions">
            <div class="icon-btn" title="Editar" onclick="openFuncionarioModal(${f.id})"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></div>
            <div class="icon-btn" title="${f.status==='ativo'?'Desativar':'Reativar'}" onclick="toggleFuncionarioStatus(${f.id})"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><path d="M12 2v10"/></svg></div>
            <div class="icon-btn danger" title="Remover" onclick="confirmRemoveFuncionario(${f.id})"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg></div>
          </div>
        </td>`;
      body.appendChild(tr);
    });
  }

  let editingFuncionarioId = null;
  function openFuncionarioModal(id){
    editingFuncionarioId = id || null;
    const title = document.getElementById('funcionarioModalTitle');
    if(id){
      const f = funcionarios.find(x => x.id === id);
      title.textContent = 'Editar funcionário';
      document.getElementById('fn-nome').value = f.nome;
      document.getElementById('fn-email').value = f.email;
      document.getElementById('fn-senha').value = '';
    } else {
      title.textContent = 'Novo funcionário';
      document.getElementById('fn-nome').value = '';
      document.getElementById('fn-email').value = '';
      document.getElementById('fn-senha').value = gerarSenha();
    }
    document.getElementById('funcionarioModal').classList.add('open');
  }

  function saveFuncionario(){
    const nome = document.getElementById('fn-nome').value.trim();
    const email = document.getElementById('fn-email').value.trim();
    const senha = document.getElementById('fn-senha').value.trim();
    if(!nome || !email){ showToast('Preenche nome e email antes de guardar.'); return; }
    if(!isValidEmail(email)){ showToast('Introduz um email de login válido.'); return; }

    const method = editingFuncionarioId ? 'PUT' : 'POST';
    const url = editingFuncionarioId
      ? `/barbearias/${BARBEARIA_ID}/users/${editingFuncionarioId}`
      : `/barbearias/${BARBEARIA_ID}/users`;

    fetch(url, {
      method, headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept':'application/json' },
      body: JSON.stringify({ name: nome, email, password: senha || undefined })
    })
    .then(r => { if(!r.ok) throw new Error(); return r.json(); })
    .then(data => {
      if(editingFuncionarioId){
        const f = funcionarios.find(x => x.id === editingFuncionarioId);
        if(f){ f.nome = nome; f.email = email; }
        showToast(`Dados de ${nome} atualizados.`);
      } else {
        funcSeq++;
        funcionarios.push({ id: data.id || funcSeq, nome, email, senha, status:'ativo', atendimentosMes: 0 });
        showToast(`${nome} adicionado à equipa.`);
      }
      closeModal('funcionarioModal');
      renderEquipa();
    })
    .catch(() => showToast('Erro ao guardar. Tenta novamente.'));
  }

  function toggleFuncionarioStatus(id){
    const f = funcionarios.find(x => x.id === id);
    const novoStatus = f.status === 'ativo' ? false : true;
    fetch(`/barbearias/${BARBEARIA_ID}/users/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept':'application/json' },
      body: JSON.stringify({ isactive: novoStatus })
    })
    .then(r => { if(!r.ok) throw new Error(); return r.json(); })
    .then(() => {
      f.status = novoStatus ? 'ativo' : 'inativo';
      renderEquipa();
      showToast(`${f.nome} agora está ${f.status}.`);
    })
    .catch(() => showToast('Erro ao alterar estado.'));
  }

  function confirmRemoveFuncionario(id){
    const f = funcionarios.find(x => x.id === id);
    document.getElementById('confirmTitle').textContent = 'Remover funcionário';
    document.getElementById('confirmDesc').textContent = `Tens a certeza que queres remover ${f.nome}?`;
    document.getElementById('confirmActionBtn').onclick = () => {
      fetch(`/barbearias/${BARBEARIA_ID}/users/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept':'application/json' }
      })
      .then(r => { if(!r.ok) throw new Error(); })
      .then(() => {
        funcionarios = funcionarios.filter(x => x.id !== id);
        closeModal('confirmModal'); renderEquipa();
        showToast(`${f.nome} removido da equipa.`);
      })
      .catch(() => showToast('Erro ao remover.'));
    };
    document.getElementById('confirmModal').classList.add('open');
  }

  // ============================================================
  // CATÁLOGO
  // ============================================================
  function renderCatalogo(){
    const grid = document.getElementById('catalogoGrid');
    grid.innerHTML = '';
    const termo = (document.getElementById('catalogoSearch').value || '').trim().toLowerCase();
    const lista = termo ? servicos.filter(s => s.nome.toLowerCase().includes(termo)) : servicos;
    document.getElementById('catalogoEmpty').style.display = lista.length ? 'none' : 'block';
    if(!lista.length) return;
    lista.forEach(s => {
      const card = document.createElement('div');
      card.className = 'catalog-card';
      card.innerHTML = `
        <div class="catalog-card-top"><div class="catalog-name">${s.nome}</div></div>
        <div class="catalog-price">${kz(s.preco)}</div>
        <div class="catalog-actions">
          <button class="btn btn-ghost btn-sm" onclick="openServicoModal(${s.id})">Editar</button>
          <button class="btn btn-danger-ghost btn-sm" onclick="confirmRemoveServico(${s.id})">Remover</button>
        </div>`;
      grid.appendChild(card);
    });
  }

  let editingServicoId = null;
  function openServicoModal(id){
    editingServicoId = id || null;
    const title = document.getElementById('servicoModalTitle');
    if(id){
      const s = servicos.find(x => x.id === id);
      title.textContent = 'Editar serviço';
      document.getElementById('sv-nome').value = s.nome;
      document.getElementById('sv-preco').value = s.preco;
    } else {
      title.textContent = 'Novo serviço';
      document.getElementById('sv-nome').value = '';
      document.getElementById('sv-preco').value = '';
    }
    document.getElementById('servicoModal').classList.add('open');
  }

  function saveServico(){
    const nome = document.getElementById('sv-nome').value.trim();
    const preco = Number(document.getElementById('sv-preco').value);
    if(!nome || !preco || preco <= 0){ showToast('Preenche o nome e um preço válido.'); return; }

    const method = editingServicoId ? 'PUT' : 'POST';
    const url = editingServicoId ? `/services/${editingServicoId}` : '/services';

    fetch(url, {
      method, headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept':'application/json' },
      body: JSON.stringify({ name: nome, price: preco, barbearia_id: BARBEARIA_ID })
    })
    .then(r => { if(!r.ok) throw new Error(); return r.json(); })
    .then(data => {
      if(editingServicoId){
        const s = servicos.find(x => x.id === editingServicoId);
        if(s){ s.nome = nome; s.preco = preco; }
        showToast(`Serviço "${nome}" atualizado.`);
      } else {
        servSeq++;
        servicos.push({ id: data.id || servSeq, nome, preco });
        showToast(`Serviço "${nome}" adicionado ao catálogo.`);
      }
      closeModal('servicoModal'); renderCatalogo();
    })
    .catch(() => showToast('Erro ao guardar serviço.'));
  }

  function confirmRemoveServico(id){
    const s = servicos.find(x => x.id === id);
    document.getElementById('confirmTitle').textContent = 'Remover serviço';
    document.getElementById('confirmDesc').textContent = `Tens a certeza que queres remover "${s.nome}" do catálogo?`;
    document.getElementById('confirmActionBtn').onclick = () => {
      fetch(`/services/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept':'application/json' }
      })
      .then(r => { if(!r.ok) throw new Error(); })
      .then(() => {
        servicos = servicos.filter(x => x.id !== id);
        closeModal('confirmModal'); renderCatalogo();
        showToast(`Serviço "${s.nome}" removido.`);
      })
      .catch(() => showToast('Erro ao remover serviço.'));
    };
    document.getElementById('confirmModal').classList.add('open');
  }

  // ============================================================
  // CONFIGURAÇÕES
  // ============================================================
  function saveSalonSettings(){
    const nome = document.getElementById('s-nome-salao').value.trim();
    if(!nome){ showToast('O nome do salão não pode ficar vazio.'); return; }
    fetch(`/barbearias/${BARBEARIA_ID}`, {
      method: 'PUT',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept':'application/json' },
      body: JSON.stringify({ name: nome })
    })
    .then(r => { if(!r.ok) throw new Error(); return r.json(); })
    .then(() => {
      document.getElementById('brandName').textContent = nome;
      document.getElementById('brandMark').textContent = nome.split(' ').filter(Boolean).slice(0,2).map(w => w[0].toUpperCase()).join('');
      showToast('Identidade do salão atualizada.');
    })
    .catch(() => showToast('Erro ao guardar. Tenta novamente.'));
  }

  function saveGestorSettings(){
    const nome = document.getElementById('s-nome').value.trim();
    const email = document.getElementById('s-email').value.trim();
    const senha = document.getElementById('s-senha').value;
    if(!nome || !email){ showToast('Preenche nome e email antes de guardar.'); return; }
    if(!isValidEmail(email)){ showToast('Introduz um email válido.'); return; }
    fetch('/profile', {
      method: 'PATCH',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept':'application/json' },
      body: JSON.stringify({ name: nome, email, ...(senha ? { password: senha, password_confirmation: senha } : {}) })
    })
    .then(r => { if(!r.ok) throw new Error(); return r.json(); })
    .then(() => {
      document.querySelector('.admin-name').textContent = nome;
      document.querySelector('.admin-email').textContent = email;
      document.getElementById('s-senha').value = '';
      showToast('Dados do gestor atualizados.');
    })
    .catch(() => showToast('Erro ao guardar. Tenta novamente.'));
  }

  // ============================================================
  // MODAL / TOAST
  // ============================================================
  function closeModal(id){ document.getElementById(id).classList.remove('open'); }
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => { if(e.target === overlay) overlay.classList.remove('open'); });
  });

  let toastTimer;
  function showToast(msg){
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 3200);
  }

  // ============================================================
  // INIT
  // ============================================================
  (function initReportDefaults(){
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    const isoDate = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}`;
    const isoMonth = `${now.getFullYear()}-${pad(now.getMonth()+1)}`;
    document.getElementById('repDia').value = isoDate;
    document.getElementById('repDia').max = isoDate;
    document.getElementById('repSemana').value = isoDate;
    document.getElementById('repSemana').max = isoDate;
    document.getElementById('repMes').value = isoMonth;
    document.getElementById('repMes').max = isoMonth;
    renderRelatorio();
  })();

  renderCatalogo();
  renderEquipa();
</script>

</body>
</html>
