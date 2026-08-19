<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Nguevela · Painel do Funcionário</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
<link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=2">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

@php
  $servicosPayload = $servicos->map(fn($s) => [
    'id' => $s->id,
    'nome' => $s->name,
    'preco' => (float) $s->price,
  ])->values();

  $pagamentosPayload = $pagamentos->map(fn($p) => [
    'id' => $p->id,
    'nome' => $p->name,
    'sub' => match($p->name) {
      'Dinheiro físico' => 'Pagamento em numerário',
      'TPA', 'Multicaixa (TPA)' => 'Cartão de débito/crédito',
      default => 'Transferência bancária',
    },
    'color' => match($p->name) {
      'Dinheiro físico' => 'var(--ok)',
      'TPA', 'Multicaixa (TPA)' => 'var(--accent)',
      default => 'var(--warn)',
    },
    'bg' => match($p->name) {
      'Dinheiro físico' => 'var(--ok-soft)',
      'TPA', 'Multicaixa (TPA)' => 'var(--accent-soft)',
      default => 'var(--warn-soft)',
    },
    'icon' => match($p->name) {
      'Dinheiro físico' => '<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
      'TPA', 'Multicaixa (TPA)' => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',
      default => '<path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>',
    },
  ])->values();
@endphp

<script>
  window.USER_DASHBOARD_DATA = {
    csrfToken: '{{ csrf_token() }}',
    userId: {{ $user->id }},
    servicos: @json($servicosPayload),
    metodosPagamento: @json($pagamentosPayload),
    faturadoHoje: {{ $faturadoHoje }},
    qtdAtendimentos: {{ $qtdAtendimentosHoje }}
  };
</script>

@vite(['resources/css/dashboard.css', 'resources/js/user-dashboard.js'])
</head>
<body>

  <div class="app-shell">

    <!-- ============ TOPBAR ============ -->
    <header class="topbar">
      <div class="topbar-brand">
        <div class="brand-mark" id="brandMark">{{ strtoupper(substr($user->barbearia->name ?? 'NB', 0, 2)) }}</div>
        <div class="brand-text">
          <div class="brand-name" id="brandName">{{ $user->barbearia->name ?? 'Nguevela Barber' }}</div>
          <div class="brand-role">ÁREA DO FUNCIONÁRIO</div>
        </div>
      </div>
      <div class="topbar-user">
        <div class="shift-dot" title="Turno ativo"></div>
        <div class="user-avatar" onclick="goToView('perfil')" id="userAvatar">
          {{ strtoupper(collect(explode(' ', $user->name))->take(2)->map(fn($w) => $w[0])->join('')) }}
        </div>
      </div>
    </header>

    <!-- ============ CONTENT ============ -->
    <main class="content">

      <!-- ============ VIEW: REGISTAR ATENDIMENTO ============ -->
      <section class="view active" id="view-registar">

        <div class="greeting">
          <div class="greeting-title" id="greetingTitle">Olá, {{ explode(' ', $user->name)[0] }} 👋</div>
          <div class="greeting-sub">Regista o atendimento em 3 toques.</div>
        </div>

        <div class="step-progress">
          <div class="step-seg" id="seg1"><div class="step-seg-fill"></div></div>
          <div class="step-seg" id="seg2"><div class="step-seg-fill"></div></div>
          <div class="step-seg" id="seg3"><div class="step-seg-fill"></div></div>
        </div>
        <div class="step-labels">
          <span id="lbl1">1 · Serviço</span>
          <span id="lbl2">2 · Pagamento</span>
          <span id="lbl3">3 · Confirmar</span>
        </div>

        <!-- STEP 1: SERVIÇO -->
        <div id="step-servico">
          <div class="service-grid" id="serviceGrid"></div>
        </div>

        <!-- STEP 2: PAGAMENTO -->
        <div id="step-pagamento" style="display:none;">
          <button class="step-back" onclick="voltarStep(1)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Trocar serviço
          </button>
          <div class="payment-grid" id="paymentGrid"></div>
        </div>

        <!-- STEP 3: CONFIRMAR -->
        <div id="step-confirmar" style="display:none;">
          <button class="step-back" onclick="voltarStep(2)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Trocar pagamento
          </button>
          <div class="summary-card">
            <div class="summary-row">
              <div class="summary-label">Serviço</div>
              <div class="summary-value" id="sumServico">—</div>
            </div>
            <div class="summary-row">
              <div class="summary-label">Pagamento</div>
              <div class="summary-value" id="sumPagamento">—</div>
            </div>
            <div class="summary-row">
              <div class="summary-label">Hora</div>
              <div class="summary-value" id="sumHora">—</div>
            </div>
            <div class="summary-total">
              <div class="summary-total-label">Valor a registar</div>
              <div class="summary-total-value" id="sumValor">—</div>
            </div>
          </div>
          <button class="btn btn-primary" onclick="confirmarAtendimento()">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Confirmar atendimento
          </button>
        </div>

      </section>

      <!-- ============ VIEW: MEU DIA (HISTÓRICO) ============ -->
      <section class="view" id="view-dia">

        <div class="greeting">
          <div class="greeting-title">Meu dia</div>
          <div class="greeting-sub" id="dataHoje">—</div>
        </div>

        <div class="kpi-mini-grid">
          <div class="kpi-mini">
            <div class="kpi-mini-label">Faturado hoje</div>
            <div class="kpi-mini-value" id="kpiFaturadoHoje" style="color:var(--accent);">{{ number_format($faturadoHoje, 0, ',', ' ') }} Kz</div>
          </div>
          <div class="kpi-mini">
            <div class="kpi-mini-label">Atendimentos</div>
            <div class="kpi-mini-value" id="kpiTotalAtend">{{ $qtdAtendimentosHoje }}</div>
          </div>
          <div class="kpi-mini">
            <div class="kpi-mini-label">Ticket médio</div>
            <div class="kpi-mini-value" id="kpiTicketMedio">{{ $qtdAtendimentosHoje > 0 ? number_format($ticketMedioHoje, 0, ',', ' ').' Kz' : '—' }}</div>
          </div>
        </div>

        <div class="section-title">Atendimentos de hoje</div>
        
        @forelse($atendimentosHoje as $atendimento)
          @php
            $badgeClass = match($atendimento->pagamento?->name) {
              'Dinheiro físico' => 'dinheiro',
              'TPA', 'Multicaixa (TPA)' => 'multicaixa',
              default => 'transferencia',
            };
            $badgeLabel = match($atendimento->pagamento?->name) {
              'Dinheiro físico' => 'Dinheiro',
              'TPA', 'Multicaixa (TPA)' => 'Multicaixa',
              default => 'Transferência',
            };
          @endphp
          <div class="history-item" id="atend-{{ $atendimento->id }}">
            <div class="history-time">{{ \Carbon\Carbon::parse($atendimento->horario)->format('H:i') }}</div>
            <div class="history-divider"></div>
            <div class="history-info">
              <div class="history-service">{{ $atendimento->service?->name ?? '—' }}</div>
              <div class="history-meta">
                <span class="pay-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
              </div>
            </div>
            <div class="history-value">{{ number_format($atendimento->valor, 0, ',', ' ') }} Kz</div>
          </div>
        @empty
          <div id="historyEmpty" class="empty-state">
            <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            <div class="empty-state-title">Ainda sem atendimentos</div>
            <div class="empty-state-sub">Regista o teu primeiro atendimento do dia.</div>
          </div>
        @endforelse
        
        <div id="newHistoryItems"></div>

      </section>

      <!-- ============ VIEW: PERFIL ============ -->
      <section class="view" id="view-perfil">

        <div class="profile-header">
          <div class="profile-avatar-wrap">
            <div class="profile-avatar" id="profileAvatar">
              {{ strtoupper(collect(explode(' ', $user->name))->take(2)->map(fn($w) => $w[0])->join('')) }}
            </div>
            <label class="avatar-upload-btn" for="avatarUploadInput" title="Carregar foto">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></svg>
            </label>
            <input type="file" id="avatarUploadInput" class="avatar-upload-input" accept="image/*" onchange="handleAvatarUpload(event)">
          </div>
          <div class="profile-name" id="profileName">{{ $user->name }}</div>
          <div class="profile-email" id="profileEmail">{{ $user->email }}</div>
          <div class="profile-salon-chip">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg>
            <span id="profileSalao">{{ $user->barbearia->name ?? 'Sem salão' }}</span>
          </div>
        </div>

        <div class="section-title">Conta</div>
        <div class="settings-card">
          <div class="list-link-row" onclick="openChangePassword()">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <div class="list-link-row-text">Alterar palavra-passe</div>
            <svg class="chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
          </div>
        </div>

        <div class="app-version">NguevelaBarber · Painel do Funcionário · v1.0</div>

      </section>

    </main>

    <!-- ============ BOTTOM NAV ============ -->
    <nav class="bottom-nav">
      <div class="nav-tab active" data-view="registar" onclick="goToView('registar')">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4 8.12 15.88M14.47 14.48 20 20M8.12 8.12 12 12"/></svg>
        Registar
      </div>
      <div class="nav-tab" data-view="dia" onclick="goToView('dia')">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
        Meu dia
      </div>
      <div class="nav-tab" data-view="perfil" onclick="goToView('perfil')">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
        Perfil
      </div>
      <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
        @csrf
        <button type="submit" class="nav-tab nav-tab-logout" title="Sair da conta">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Sair
        </button>
      </form>
    </nav>

  </div>

  <!-- ============ SUCCESS OVERLAY ============ -->
  <div class="success-overlay" id="successOverlay">
    <div class="success-ring">
      <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
    </div>
    <div class="success-title">Atendimento registado!</div>
    <div class="success-value" id="successValor">—</div>
    <div class="success-sub" id="successSub">A voltar ao início...</div>
  </div>

  <!-- ============ MODAL: CONFIRMAR SAÍDA ============ -->
  <div class="modal-overlay" id="logoutModal">
    <div class="modal">
      <div class="modal-grip"></div>
      <div class="modal-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
      </div>
      <div class="modal-title">Sair da conta?</div>
      <div class="modal-desc">Vais precisar de introduzir a tua palavra-passe novamente para voltar a registar atendimentos.</div>
      <div class="btn-row">
        <button class="btn btn-ghost" onclick="closeModal('logoutModal')">Cancelar</button>
        <button class="btn btn-danger-ghost" style="background:var(--danger); color:#fff; border:none;" onclick="document.getElementById('logoutForm').submit()">Sair</button>
      </div>
    </div>
  </div>

  <!-- ============ TOAST ============ -->
  <div class="toast" id="toast">
    <div class="toast-body">
      <span class="toast-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </span>
      <span class="toast-msg" id="toastMsg">Feito.</span>
      <button class="toast-undo" id="toastUndoBtn" onclick="handleToastUndo()">DESFAZER</button>
    </div>
    <div class="toast-progress" id="toastProgress">
      <div class="toast-progress-fill" id="toastProgressFill"></div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>