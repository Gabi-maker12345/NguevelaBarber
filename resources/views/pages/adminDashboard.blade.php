<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nguevela · Master Admin</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
<link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=2">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<script>
  window.ADMIN_DATA = {
    csrfToken: '{{ csrf_token() }}',
    saloes: @json($barbeariasPayload ?? [])
  };
</script>

@vite(['resources/css/dashboard.css', 'resources/js/admin-dashboard.js'])
</head>
<body>

<div class="app">

  <!-- ============ SIDEBAR (desktop) ============ -->
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark"><img src="{{ asset('images/logo.png') }}" alt="ng" style="width:100%; height:100%; object-fit:cover; border-radius:inherit;"></div>
      <div class="brand-text">
        <div class="brand-name">Nguevela</div>
        <div class="brand-role">ADMIN MASTER</div>
      </div>
    </div>

    <div class="nav-group-label">Plataforma</div>
    <div class="nav-item active" data-view="overview" onclick="switchView('overview')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-7h6v7"/></svg>
      Visão geral
    </div>
    <div class="nav-item" data-view="billing" onclick="switchView('billing')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/></svg>
      Faturação SaaS
    </div>
    <div class="nav-item" data-view="settings" onclick="switchView('settings')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Configurações
    </div>

    <div class="sidebar-footer">
      <div class="admin-chip">
        <div style="display:flex; align-items:center; gap:10px; min-width:0;">
          <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}</div>
          <div style="min-width:0;">
            <div class="admin-name">{{ auth()->user()->name ?? 'Admin Master' }}</div>
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
          <h1 class="page-title" id="pageTitle">Salões registados</h1>
          <div class="page-sub" id="pageSub">Gere todos os estabelecimentos, mensalidades e acessos da plataforma.</div>
        </div>
        <div class="page-header-actions" id="topbarActions">
          <div class="search-field">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input id="searchInput" type="text" placeholder="Procurar salão ou dono..." oninput="filterTable()">
          </div>
          <button class="btn btn-primary desktop-only" onclick="openCreateModal()">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Novo salão
          </button>
        </div>
      </div>

      <!-- ============ VIEW: VISÃO GERAL / SALÕES ============ -->
      <section class="view active" id="view-overview">

        <!-- ---------- KPIs ---------- -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">Total de salões</div>
              <div class="kpi-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-7h6v7"/></svg>
              </div>
            </div>
            <div class="kpi-value">{{ $totalSaloes }}</div>
            <div class="kpi-delta">em todo o país</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">Ativos</div>
              <div class="kpi-icon" style="background:var(--ok-soft); color:var(--ok);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
              </div>
            </div>
            <div class="kpi-value">{{ $saloesAtivos }}</div>
            <div class="kpi-delta up">{{ $saloesAtivos > 0 ? round(($saloesAtivos/$totalSaloes)*100) : 0 }}% da base</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">A expirar em 5 dias</div>
              <div class="kpi-icon" style="background:var(--accent-soft); color:var(--accent);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              </div>
            </div>
            <div class="kpi-value">{{ $saloesAExpirar }}</div>
            <div class="kpi-delta warn">requer atenção</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-label">Receita mensal (Kz)</div>
              <div class="kpi-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              </div>
            </div>
            <div class="kpi-value">{{ number_format($receitaMensal, 0, ',', '.') }} Kz</div>
            <div class="kpi-delta">soma das mensalidades ativas</div>
          </div>
        </div>

        <!-- ---------- FILTERS ---------- -->
        <div class="filter-bar">
          <div class="filter-chip active" data-filter="todos" onclick="setFilter('todos')">Todos</div>
          <div class="filter-chip" data-filter="ativo" onclick="setFilter('ativo')">Ativos</div>
          <div class="filter-chip" data-filter="expirar" onclick="setFilter('expirar')">A expirar</div>
          <div class="filter-chip" data-filter="suspenso" onclick="setFilter('suspenso')">Suspensos</div>
        </div>

        <!-- ---------- TABLE PANEL ---------- -->
        <div class="panel">
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>SALÃO</th>
                  <th>PLANO</th>
                  <th>EXPIRA EM</th>
                  <th>STATUS</th>
                  <th>EQUIPA</th>
                  <th style="text-align:right;">AÇÕES</th>
                </tr>
              </thead>
              <tbody id="saloesTableBody">
                @forelse($barbearias as $barbearia)
                  @php
                    $diasRestantes = $barbearia->diasRestantes();
                    $statusClass = 'inativo';
                    $statusText = 'Inativo';
                    
                    if (!$barbearia->isactive) {
                        $statusClass = 'suspenso';
                        $statusText = 'Suspenso';
                    } elseif ($diasRestantes <= 0) {
                        $statusClass = 'suspenso';
                        $statusText = 'Expirado';
                    } elseif ($diasRestantes <= 5) {
                        $statusClass = 'expirar';
                        $statusText = "Expirando em {$diasRestantes} dias";
                    } else {
                        $statusClass = 'ativo';
                        $statusText = 'Ativo';
                    }
                  @endphp
                  <tr data-status="{{ $statusClass }}" data-search="{{ strtolower($barbearia->name . ' ' . ($barbearia->gestor ?? '')) }}">
                    <td class="cell-salon">
                      <div class="salon-cell">
                        <div class="salon-logo">{{ strtoupper(substr($barbearia->name, 0, 2)) }}</div>
                        <div>
                          <div class="salon-name">{{ $barbearia->name }}</div>
                          <div class="salon-owner">{{ $barbearia->gestor ?? 'Sem Gestor' }} ({{ $barbearia->municipio ?? 'Luanda' }})</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="price-tag">{{ number_format($barbearia->plano, 0, ',', '.') }} Kz</div>
                      <div class="plan-tag">Mensal</div>
                    </td>
                    <td>
                      @if($barbearia->subscricao_expira_em)
                        <div>{{ \Carbon\Carbon::parse($barbearia->subscricao_expira_em)->format('d/m/Y') }}</div>
                        <div class="muted">{{ $diasRestantes > 0 ? "Restam {$diasRestantes} dias" : 'Expirado' }}</div>
                      @else
                        <div class="muted">Sem data</div>
                      @endif
                    </td>
                    <td>
                      <span class="status-badge {{ $statusClass }}">
                        <span class="status-dot"></span>
                        {{ $statusText }}
                      </span>
                    </td>
                    <td>{{ $barbearia->users_count ?? 1 }} membro(s)</td>
                    <td class="cell-actions">
                      <div style="display:flex; gap:6px; justify-content:flex-end;">
                        <button class="btn btn-ghost btn-sm" title="Ver detalhes" onclick="openViewModal({{ $barbearia->id }})">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                          Ver
                        </button>
                        <button class="btn btn-ghost btn-sm" title="Editar salão" onclick="openEditModal({{ $barbearia->id }})">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                          Editar
                        </button>
                        <form action="{{ route('barbearias.destroy', $barbearia->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Tem certeza que deseja remover este salão?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger-ghost btn-sm" title="Remover salão">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                            Remover
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="empty-state">
                      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-7h6v7"/></svg>
                      <div class="empty-state-title">Nenhum salão encontrado</div>
                      <div>Comece adicionando uma nova barbearia.</div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </section>

      <!-- ============ VIEW: FATURAÇÃO SAAS ============ -->
      <section class="view" id="view-billing">

        <div class="summary-row">
          <div class="summary-card">
            <div class="summary-label">Mensalidades ativas este mês</div>
            <div class="summary-value">{{ number_format($receitaMensal, 0, ',', '.') }} Kz</div>
            <div class="summary-note">{{ $saloesAtivos }} salão(ões) com subscrição em dia</div>
          </div>
          <div class="summary-card">
            <div class="summary-label">Em risco de suspensão</div>
            <div class="summary-value" style="color:var(--accent);">{{ $saloesAExpirar * 10000 }} Kz</div>
            <div class="summary-note">{{ $saloesAExpirar }} salão(ões) a expirar em 5 dias</div>
          </div>
          <div class="summary-card">
            <div class="summary-label">Preço da mensalidade</div>
            <div class="summary-value">10.000 Kz</div>
            <div class="summary-note">Plano único fixo por salão</div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">
            <div class="panel-title">Histórico de mensalidades por salão</div>
            <button class="btn btn-ghost" onclick="switchView('overview')">Ver salões</button>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Salão</th>
                  <th>Plano</th>
                  <th>Valor mensal</th>
                  <th>Validade</th>
                  <th>Estado da subscrição</th>
                </tr>
              </thead>
              <tbody>
                @forelse($barbearias as $barbearia)
                  @php
                    $diasRestantes = $barbearia->diasRestantes();
                    $statusClass = $diasRestantes > 5 ? 'ativo' : ($diasRestantes > 0 ? 'expirar' : 'suspenso');
                    $statusText = $diasRestantes > 5 ? 'Em dia' : ($diasRestantes > 0 ? "Expira em {$diasRestantes}d" : 'Expirado');
                  @endphp
                  <tr>
                    <td><strong>{{ $barbearia->name }}</strong></td>
                    <td>Plano Mensal SaaS</td>
                    <td><span class="price-tag">{{ number_format($barbearia->plano, 0, ',', '.') }} Kz</span></td>
                    <td>{{ $barbearia->subscricao_expira_em ? \Carbon\Carbon::parse($barbearia->subscricao_expira_em)->format('d/m/Y') : '—' }}</td>
                    <td>
                      <span class="status-badge {{ $statusClass }}">
                        <span class="status-dot"></span>
                        {{ $statusText }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="empty-state">
                      <div class="empty-state-title">Sem registos de faturação</div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </section>

      <!-- ============ VIEW: CONFIGURAÇÕES ============ -->
      <section class="view" id="view-settings">

        <div class="settings-card">
          <div class="settings-card-title">Preço da mensalidade SaaS</div>
          <div class="settings-card-desc">Valor cobrado mensalmente a cada barbearia registada na plataforma.</div>
          <div class="field-block">
            <label class="field-label">VALOR MENSAL (KZ)</label>
            <input class="field-input" type="text" value="10.000 Kz" disabled style="background:var(--bg-main);">
          </div>
          <div class="muted">O valor de 10.000 Kz é fixo para todas as contas.</div>
        </div>

        <div class="settings-card">
          <div class="settings-card-title">Perfil do Admin Master</div>
          <div class="settings-card-desc">Credenciais de acesso à conta principal da plataforma.</div>
          <div class="field-block">
            <label class="field-label">NOME</label>
            <input class="field-input" type="text" value="{{ auth()->user()->name ?? 'Admin Master' }}">
          </div>
          <div class="field-block">
            <label class="field-label">EMAIL (PRÉ-DEFINIDO)</label>
            <input class="field-input" type="email" value="{{ auth()->user()->email ?? '' }}" readonly style="background:var(--bg-main); opacity:0.85;">
            <div class="muted" style="font-size:11px; margin-top:3px;">O email do Admin Master é pré-definido e não pode ser alterado pelo site.</div>
          </div>
        </div>


      </section>

    </div>

  </main>
</div>

<!-- ============ MOBILE BOTTOM NAV ============ -->
<nav class="mobile-nav">
  <div class="mobile-nav-inner">
    <div class="mobile-nav-item active" data-view="overview" onclick="switchView('overview')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-7h6v7"/></svg>
      Salões
    </div>
    <div class="mobile-nav-item" data-view="billing" onclick="switchView('billing')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
      Faturação
    </div>
    <div class="mobile-nav-item" data-view="settings" onclick="switchView('settings')">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Config
    </div>
  </div>
</nav>

<button class="mobile-fab" onclick="openCreateModal()">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
</button>

<!-- ============ MODAL: NOVA EMPRESA (assistente em 2 passos) ============ -->
<div class="modal-overlay" id="salonModal">
  <div class="modal">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="salonModalTitle">Nova empresa</div>
      </div>
      <div class="modal-close" onclick="closeModal('salonModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>

    <div class="step-dots">
      <div class="step-dot current" id="dot-1"></div>
      <div class="step-dot" id="dot-2"></div>
    </div>
    <div class="step-label" id="stepLabel">Passo 1 de 2 · Empresa e gestor</div>

    <div class="plan-badge">
      <span class="plan-badge-label">Plano da plataforma</span>
      <span class="plan-badge-value">Mensal · 10.000 Kz</span>
    </div>

    <form id="salonForm" action="{{ route('barbearias.store') }}" method="POST">
        @csrf

      <!-- PASSO 1: EMPRESA + GESTOR -->
      <div class="wizard-step active" id="wizardStep1">

        <div class="section-label">Empresa</div>
        <div class="form-grid">
          <div class="field-block full">
            <label class="field-label">NOME DA EMPRESA / LOJA</label>
            <input class="field-input" name="name" type="text" placeholder="Ex: Barbearia Central Luanda" required>
          </div>
          <div class="field-block full">
            <label class="field-label">MUNICÍPIO</label>
            <input class="field-input" name="municipio" type="text" placeholder="Ex: Talatona" required>
          </div>
        </div>

        <div class="section-label" style="margin-top:6px;">Gestor da loja</div>
        <div class="form-grid">
          <div class="field-block full">
            <label class="field-label">NOME DO GESTOR</label>
            <input class="field-input" name="gestor" type="text" placeholder="Ex: João Neto" required>
          </div>
          <div class="field-block">
            <label class="field-label">EMAIL DE LOGIN</label>
            <input class="field-input" name="email" type="email" placeholder="gestor@empresa.ao" required>
          </div>
          <div class="field-block">
            <label class="field-label">TELEFONE</label>
            <input class="field-input" name="number" type="text" placeholder="9XX XXX XXX" required>
          </div>
          
          <input type="hidden" name="admin_id" value="{{ Auth::id() }}">
          <input type="hidden" name="plano" value="10000">

          <div class="field-block full">
            <label class="field-label">SENHA DE ACESSO</label>
            <div class="field-with-action">
              <input class="field-input" id="fn-pass-new" name="password" type="password" minlength="8" placeholder="Senha (Mín 6 chars)" style="padding-right:112px;" required>
              <div class="field-inline-actions">
                <button type="button" class="field-inline-action" onclick="togglePasswordVisibility('fn-pass-new', this)">Mostrar</button>
                <button type="button" class="field-inline-action" onclick="copiarValor(this)">Copiar</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- PASSO 2: EQUIPA (OPCIONAL) -->
      <div class="wizard-step" id="wizardStep2">
        <div class="section-label">Funcionários iniciais</div>
        <div class="modal-desc" style="margin-bottom:14px;">Podes adicionar barbeiros agora ou deixar para o gestor adicionar mais tarde.</div>

        <div class="funcionario-list" id="funcionarioList"></div>

        <div id="funcionarioEmptyState" class="funcionario-empty">
          Nenhum funcionário adicionado ainda.
        </div>

        <button type="button" class="add-funcionario-btn" onclick="addFuncionarioRow()">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
          Adicionar funcionário
        </button>

        <div class="skip-note">
          Se não adicionares nenhum funcionário agora, a conta da empresa será criada apenas com o gestor.
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" id="btnBackStep" onclick="prevWizardStep()" style="display:none;">Voltar</button>
        <button type="button" class="btn btn-ghost" onclick="closeModal('salonModal')">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnNextStep" onclick="nextWizardStep()">Avançar</button>
        <button type="submit" class="btn btn-primary" id="btnSubmitForm" style="display:none;">Criar empresa</button>
      </div>
    </form>
  </div>
</div>

<!-- ============ MODAL: VER EMPRESA ============ -->
<div class="modal-overlay" id="viewSalonModal">
  <div class="modal" style="max-width:540px;">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="v-nome">Detalhes da empresa</div>
        <div class="modal-desc" style="margin-bottom:0;" id="v-municipio">Informações da conta e equipa associada.</div>
      </div>
      <div class="modal-close" onclick="closeModal('viewSalonModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>

    <div class="section-label" style="margin-top:16px;">Empresa e Gestor</div>
    <div class="summary-card" style="margin-bottom:16px;">
      <div class="summary-row"><div class="summary-label">Gestor da Loja</div><div class="summary-value" id="v-dono">—</div></div>
      <div class="summary-row"><div class="summary-label">Email de Login</div><div class="summary-value" id="v-email">—</div></div>
      <div class="summary-row"><div class="summary-label">Telefone</div><div class="summary-value" id="v-telefone">—</div></div>
      <div class="summary-row"><div class="summary-label">Mensalidade SaaS</div><div class="summary-value" id="v-plano">—</div></div>
      <div class="summary-row"><div class="summary-label">Estado da Conta</div><div class="summary-value" id="v-status">—</div></div>
    </div>

    <div class="modal-actions">
      <button type="button" class="btn btn-ghost" onclick="closeModal('viewSalonModal')">Fechar</button>
      <button type="button" class="btn btn-primary" id="v-btnEdit">Editar dados</button>
    </div>
  </div>
</div>

<!-- ============ MODAL: EDITAR EMPRESA ============ -->
<div class="modal-overlay" id="detailModal">
  <div class="modal" style="max-width:600px;">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="detailTitle">Editar empresa</div>
        <div class="modal-desc" style="margin-bottom:0;">Atualiza os dados da barbearia, gestor, palavra-passe ou equipa.</div>
      </div>
      <div class="modal-close" onclick="closeModal('detailModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>

    <form id="editSalonForm" action="" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="isactive" id="d-isactive" value="1">

      <div class="section-label" style="margin-top:16px;">Empresa</div>
      <div class="form-grid">
        <div class="field-block full">
          <label class="field-label">NOME DA EMPRESA / LOJA</label>
          <input class="field-input" id="d-nome" name="name" type="text" required>
        </div>
        <div class="field-block full">
          <label class="field-label">MUNICÍPIO</label>
          <input class="field-input" id="d-municipio" name="municipio" type="text" required>
        </div>
      </div>

      <div class="section-label" style="margin-top:6px;">Gestor da loja</div>
      <div class="form-grid">
        <div class="field-block full">
          <label class="field-label">NOME DO GESTOR</label>
          <input class="field-input" id="d-dono" name="gestor" type="text" required>
        </div>
        <div class="field-block">
          <label class="field-label">EMAIL DE LOGIN (PRÉ-DEFINIDO)</label>
          <input class="field-input" id="d-email" type="email" readonly style="background:var(--bg-main); opacity:0.85;">
        </div>
        <div class="field-block">
          <label class="field-label">TELEFONE</label>
          <input class="field-input" id="d-telefone" name="number" type="text" required>
        </div>
        <div class="field-block full">
          <label class="field-label">PLANO / MENSALIDADE (KZ)</label>
          <input class="field-input" id="d-plano" name="plano" type="number" required>
        </div>
      </div>

      <div class="section-label" style="margin-top:10px;">Palavra-passe</div>
      <div class="form-grid">
        <div class="field-block full">
          <label class="field-label">PALAVRA-PASSE ATUAL</label>
          <div class="field-with-action">
            <input class="field-input" id="d-senha-atual" type="password" readonly style="background:var(--bg-main); padding-right:75px;" placeholder="—">
            <div class="field-inline-actions">
              <button type="button" id="d-senha-atual-btn" class="field-inline-action" onclick="togglePasswordVisibility('d-senha-atual', this)">Ver</button>
            </div>
          </div>
        </div>
        <div class="field-block full">
          <label class="field-label">ALTERAR PALAVRA-PASSE (OPCIONAL - MÍN 6 CHARS)</label>
          <div class="field-with-action">
            <input class="field-input" id="d-senha" name="password" type="password" minlength="8" placeholder="Nova palavra-passe" style="padding-right:75px;">
            <div class="field-inline-actions">
              <button type="button" class="field-inline-action" onclick="togglePasswordVisibility('d-senha', this)">Mostrar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- SEÇÃO: EQUIPA DA EMPRESA (FUNCIONÁRIOS CRIADOS NA ADIÇÃO E DEPOIS) -->
      <div style="border-top:1px solid var(--border); margin-top:18px; padding-top:16px;">
        <div class="section-label" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
          <span>Equipa da Empresa (Funcionários)</span>
          <button type="button" class="btn btn-secondary btn-sm" onclick="openAdminAddEmployeeModal()">+ Adicionar funcionário</button>
        </div>
        <div id="adminEquipaList"></div>
        <div id="adminEquipaEmpty" class="funcionario-empty" style="display:none; margin-bottom:14px;">
          Nenhum funcionário registado nesta empresa.
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-danger-ghost" id="detailSuspendBtn" onclick="toggleActiveStatus()">Suspender</button>
        <button type="button" class="btn btn-ghost" onclick="closeModal('detailModal')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar alterações</button>
      </div>
    </form>
  </div>
</div>

<!-- ============ MODAL: CRIAR / EDITAR FUNCIONÁRIO (PELO ADMIN MASTER) ============ -->
<div class="modal-overlay" id="adminEmployeeModal">
  <div class="modal" style="max-width:440px;">
    <div class="modal-header">
      <div class="modal-title" id="adminEmployeeModalTitle">Editar funcionário</div>
      <div class="modal-close" onclick="closeModal('adminEmployeeModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>
    <div class="modal-desc">Gestão direta de funcionários desta empresa.</div>
    <form id="adminEmployeeForm">
      <input type="hidden" id="ae-id">
      <div class="field-block">
        <label class="field-label">NOME COMPLETO</label>
        <input class="field-input" id="ae-nome" type="text" placeholder="Ex: Carlos Neto" required>
      </div>
      <div class="field-block">
        <label class="field-label">EMAIL DE LOGIN (PRÉ-DEFINIDO AO CRIAR)</label>
        <input class="field-input" id="ae-email" type="email" placeholder="carlos@empresa.ao" required>
      </div>

      <div class="field-block" id="ae-senha-atual-wrap">
        <label class="field-label">PALAVRA-PASSE ATUAL</label>
        <input class="field-input" type="text" value="••••••••" disabled style="background:var(--bg-main);">
      </div>

      <div class="field-block">
        <label class="field-label">NOVA PALAVRA-PASSE (MÍN 6 CHARS)</label>
        <div class="field-with-action">
          <input class="field-input" id="ae-senha" type="password" minlength="8" placeholder="Deixa em branco para manter a atual" style="padding-right:70px;">
          <div class="field-inline-actions">
            <button type="button" class="field-inline-action" onclick="togglePasswordVisibility('ae-senha', this)">Mostrar</button>
          </div>
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal('adminEmployeeModal')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar funcionário</button>
      </div>
    </form>
  </div>
</div>

<!-- ============ MODAL: VER FUNCIONÁRIO (PELO ADMIN MASTER) ============ -->
<div class="modal-overlay" id="adminViewEmployeeModal">
  <div class="modal" style="max-width:420px;">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="ve-nome">Detalhes do funcionário</div>
        <div class="modal-desc" style="margin-bottom:0;">Informações da conta.</div>
      </div>
      <div class="modal-close" onclick="closeModal('adminViewEmployeeModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>

    <div class="summary-card" style="margin-top:16px; margin-bottom:16px;">
      <div class="summary-row"><div class="summary-label">Nome Completo</div><div class="summary-value" id="ve-nome-val">—</div></div>
      <div class="summary-row"><div class="summary-label">Email de Login</div><div class="summary-value" id="ve-email-val">—</div></div>
      <div class="summary-row"><div class="summary-label">Estado</div><div class="summary-value" id="ve-status-val">—</div></div>
    </div>

    <div class="modal-actions">
      <button type="button" class="btn btn-ghost" onclick="closeModal('adminViewEmployeeModal')">Fechar</button>
      <button type="button" class="btn btn-primary" id="ve-btnEdit">Editar dados</button>
    </div>
  </div>
</div>

<!-- ============ TOAST ============ -->
<div class="toast" id="toast">
  <span class="toast-ok" id="toastIcon">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
  </span>
  <span id="toastMsg">Alterações guardadas com sucesso.</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
