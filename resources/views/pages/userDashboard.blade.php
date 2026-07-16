<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Nguevela · Painel do Funcionário</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg-deep:#0B0B0D;
    --bg-panel:#141416;
    --bg-elevated:#1A1A1D;
    --field-bg:#1F1F22;
    --field-border:#333336;
    --line:#2A2A2D;
    --gold:#D4AF37;
    --gold-soft:rgba(212,175,55,0.12);
    --gold-dark:#241C05;
    --text-primary:#F5F3EE;
    --text-secondary:#8F8D87;
    --text-faint:#5C5A54;
    --ok:#4CAF6D;
    --ok-soft:rgba(76,175,109,0.12);
    --warn:#E0A93B;
    --warn-soft:rgba(224,169,59,0.12);
    --danger:#D64545;
    --danger-soft:rgba(214,69,69,0.12);
    --info:#4C93AF;
    --info-soft:rgba(76,147,175,0.12);
    --radius:14px;
    --topbar-h:64px;
    --nav-h:68px;
  }

  *{ box-sizing:border-box; margin:0; padding:0; -webkit-tap-highlight-color:transparent; }

  button:focus-visible, a:focus-visible, input:focus-visible{
    outline:2px solid var(--gold);
    outline-offset:2px;
  }

  html,body{
    min-height:100%;
    font-family:'Inter', sans-serif;
    background:#050506;
    color:var(--text-primary);
    overflow-x:hidden;
    max-width:100vw;
  }

  ::-webkit-scrollbar{ width:6px; height:6px; }
  ::-webkit-scrollbar-thumb{ background:var(--field-border); border-radius:8px; }

  button{ font-family:'Inter', sans-serif; }

  /* ============ APP SHELL (mobile-first, centrado em desktop) ============ */
  .app-shell{
    max-width:460px;
    min-height:100vh;
    min-height:100dvh;
    margin:0 auto;
    background:var(--bg-deep);
    position:relative;
    display:flex;
    flex-direction:column;
    overflow-x:hidden;
    box-shadow:0 0 60px rgba(0,0,0,0.6);
  }

  @media (min-width:640px){
    body{ background:radial-gradient(circle at 50% 0%, #16161a 0%, #050506 65%); padding:28px 0; }
    .app-shell{ min-height:calc(100dvh - 56px); border-radius:28px; overflow:hidden; border:1px solid var(--line); }
  }

  /* ---------- TOPBAR ---------- */
  .topbar{
    position:sticky; top:0; z-index:40;
    height:var(--topbar-h);
    display:flex; align-items:center; justify-content:space-between;
    gap:10px; padding:0 18px;
    background:rgba(20,20,22,0.9);
    backdrop-filter:blur(10px);
    border-bottom:1px solid var(--line);
    flex-shrink:0;
  }
  .topbar-brand{ display:flex; align-items:center; gap:10px; min-width:0; }
  .brand-mark{
    width:34px; height:34px; border-radius:9px; flex-shrink:0;
    background:var(--gold); color:var(--gold-dark);
    display:flex; align-items:center; justify-content:center;
    font-family:'Oswald', sans-serif; font-weight:700; font-size:14px;
    overflow:hidden;
  }
  .brand-mark img{ width:100%; height:100%; object-fit:cover; }
  .brand-text{ min-width:0; line-height:1.2; }
  .brand-name{ font-family:'Oswald', sans-serif; font-weight:600; font-size:13.5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:150px; }
  .brand-role{ font-size:10.5px; letter-spacing:0.6px; color:var(--gold); }

  .topbar-user{ display:flex; align-items:center; gap:9px; }
  .user-avatar{
    width:34px; height:34px; border-radius:50%;
    background:linear-gradient(135deg, var(--gold), #8a6d1f);
    display:flex; align-items:center; justify-content:center;
    color:var(--gold-dark); font-weight:700; font-size:12.5px;
    flex-shrink:0; cursor:pointer;
    border:2px solid var(--bg-panel);
    background-size:cover; background-position:center;
  }
  .shift-dot{ width:8px; height:8px; border-radius:50%; background:var(--ok); box-shadow:0 0 0 3px var(--ok-soft); }

  /* ---------- CONTENT ---------- */
  .content{
    flex:1;
    padding:18px 18px calc(var(--nav-h) + 26px);
    width:100%;
    overflow-x:hidden;
  }

  .view{ display:none; animation:fadeIn .2s ease; }
  .view.active{ display:block; }
  @keyframes fadeIn{ from{ opacity:0; transform:translateY(6px); } to{ opacity:1; transform:none; } }

  .greeting{ margin-bottom:18px; }
  .greeting-title{ font-family:'Oswald', sans-serif; font-weight:600; font-size:21px; }
  .greeting-sub{ font-size:12.5px; color:var(--text-secondary); margin-top:3px; }

  /* ---------- STEP PROGRESS ---------- */
  .step-progress{ display:flex; align-items:center; gap:6px; margin-bottom:20px; }
  .step-seg{ flex:1; height:4px; border-radius:4px; background:var(--field-bg); overflow:hidden; }
  .step-seg-fill{ height:100%; width:0%; background:var(--gold); border-radius:4px; transition:width .25s ease; }
  .step-seg.done .step-seg-fill{ width:100%; }
  .step-seg.current .step-seg-fill{ width:100%; }

  .step-labels{ display:flex; justify-content:space-between; font-size:10.5px; color:var(--text-faint); margin-bottom:16px; letter-spacing:0.4px; text-transform:uppercase; }
  .step-labels span.active{ color:var(--gold); font-weight:600; }

  .step-back{
    display:inline-flex; align-items:center; gap:6px;
    background:none; border:none; color:var(--text-secondary);
    font-size:12.5px; font-weight:500; cursor:pointer; margin-bottom:14px; padding:4px 0;
  }
  .step-back:hover{ color:var(--gold); }

  /* ---------- SERVICE GRID (toque 1) ---------- */
  .service-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
  .service-card{
    background:var(--bg-panel); border:1.5px solid var(--line); border-radius:var(--radius);
    padding:18px 14px; display:flex; flex-direction:column; gap:10px;
    cursor:pointer; transition:border-color .15s ease, background .15s ease, transform .1s ease;
    min-height:100px;
  }
  .service-card:active{ transform:scale(0.97); }
  .service-card:hover{ border-color:var(--gold); }
  .service-card.selected{ border-color:var(--gold); background:var(--gold-soft); }
  .service-icon{
    width:34px; height:34px; border-radius:9px; background:var(--field-bg);
    display:flex; align-items:center; justify-content:center; color:var(--gold);
  }
  .service-name{ font-size:13.5px; font-weight:600; line-height:1.25; }
  .service-price{ font-family:'Oswald', sans-serif; font-weight:600; font-size:15px; color:var(--gold); margin-top:auto; }

  /* ---------- PAYMENT GRID (toque 2) ---------- */
  .payment-grid{ display:flex; flex-direction:column; gap:12px; }
  .payment-card{
    display:flex; align-items:center; gap:14px;
    background:var(--bg-panel); border:1.5px solid var(--line); border-radius:var(--radius);
    padding:16px 16px; cursor:pointer; transition:border-color .15s ease, background .15s ease, transform .1s ease;
  }
  .payment-card:active{ transform:scale(0.98); }
  .payment-card:hover{ border-color:var(--gold); }
  .payment-card.selected{ border-color:var(--gold); background:var(--gold-soft); }
  .payment-icon{
    width:42px; height:42px; border-radius:11px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
  }
  .payment-name{ font-size:14.5px; font-weight:600; }
  .payment-sub{ font-size:11.5px; color:var(--text-faint); margin-top:1px; }
  .payment-check{ margin-left:auto; color:var(--gold); opacity:0; transition:opacity .15s ease; }
  .payment-card.selected .payment-check{ opacity:1; }

  /* ---------- CONFIRM (toque 3) ---------- */
  .summary-card{
    background:var(--bg-panel); border:1px solid var(--line); border-radius:var(--radius);
    padding:22px 20px; margin-bottom:18px;
  }
  .summary-row{ display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--line); }
  .summary-row:last-child{ border-bottom:none; }
  .summary-label{ font-size:12px; color:var(--text-faint); text-transform:uppercase; letter-spacing:0.5px; }
  .summary-value{ font-size:14px; font-weight:600; text-align:right; }
  .summary-total{ display:flex; align-items:center; justify-content:space-between; margin-top:16px; padding-top:16px; border-top:1px dashed var(--field-border); }
  .summary-total-label{ font-size:13px; color:var(--text-secondary); }
  .summary-total-value{ font-family:'Oswald', sans-serif; font-weight:700; font-size:26px; color:var(--gold); }

  .btn{
    display:flex; align-items:center; justify-content:center; gap:9px;
    width:100%; font-family:'Inter', sans-serif; font-weight:700; font-size:15px;
    padding:16px; border-radius:12px; border:none; cursor:pointer;
    transition:filter .15s ease, transform .1s ease;
  }
  .btn:active{ transform:scale(0.98); }
  .btn-gold{ background:var(--gold); color:var(--gold-dark); box-shadow:0 8px 22px rgba(212,175,55,0.25); }
  .btn-gold:hover{ filter:brightness(1.06); }
  .btn-ghost{ background:var(--bg-elevated); color:var(--text-primary); border:1px solid var(--field-border); }
  .btn-ghost:hover{ border-color:var(--gold); }
  .btn-danger-ghost{ background:transparent; color:var(--danger); border:1px solid var(--danger); }
  .btn-danger-ghost:hover{ background:var(--danger-soft); }
  .btn-row{ display:flex; gap:10px; }
  .btn-row .btn{ flex:1; }

  /* ---------- SUCCESS OVERLAY ---------- */
  .success-overlay{
    position:fixed; inset:0; z-index:200; background:var(--bg-deep);
    display:none; align-items:center; justify-content:center; flex-direction:column; gap:16px;
    text-align:center; padding:30px;
  }
  .success-overlay.show{ display:flex; }
  .success-ring{
    width:88px; height:88px; border-radius:50%; background:var(--ok-soft);
    display:flex; align-items:center; justify-content:center; color:var(--ok);
    animation:pop .35s ease;
  }
  @keyframes pop{ 0%{ transform:scale(0.5); opacity:0; } 70%{ transform:scale(1.08); } 100%{ transform:scale(1); opacity:1; } }
  .success-title{ font-family:'Oswald', sans-serif; font-weight:600; font-size:20px; }
  .success-sub{ font-size:13px; color:var(--text-secondary); max-width:280px; }
  .success-value{ font-family:'Oswald', sans-serif; font-weight:700; font-size:30px; color:var(--gold); }

  /* ---------- KPI MINI (histórico) ---------- */
  .kpi-mini-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:20px; }
  .kpi-mini{ background:var(--bg-panel); border:1px solid var(--line); border-radius:12px; padding:14px 12px; }
  .kpi-mini-label{ font-size:10px; color:var(--text-faint); text-transform:uppercase; letter-spacing:0.4px; margin-bottom:6px; }
  .kpi-mini-value{ font-family:'Oswald', sans-serif; font-weight:600; font-size:16.5px; }
  .kpi-mini-value.gold{ color:var(--gold); }

  .section-title{ font-family:'Oswald', sans-serif; font-weight:600; font-size:14.5px; margin-bottom:12px; }

  .history-item{
    display:flex; align-items:center; gap:12px;
    background:var(--bg-panel); border:1px solid var(--line); border-radius:12px;
    padding:13px 14px; margin-bottom:10px;
  }
  .history-time{
    width:44px; text-align:center; flex-shrink:0;
    font-family:'Oswald', sans-serif; font-weight:600; font-size:13px; color:var(--gold);
  }
  .history-divider{ width:1px; align-self:stretch; background:var(--line); flex-shrink:0; }
  .history-info{ flex:1; min-width:0; }
  .history-service{ font-size:13.5px; font-weight:600; }
  .history-meta{ display:flex; align-items:center; gap:6px; margin-top:3px; }
  .pay-badge{
    font-size:10px; font-weight:600; padding:3px 8px; border-radius:20px;
    display:inline-flex; align-items:center; gap:4px; white-space:nowrap;
  }
  .pay-badge.dinheiro{ background:var(--ok-soft); color:var(--ok); }
  .pay-badge.multicaixa{ background:var(--info-soft); color:var(--info); }
  .pay-badge.transferencia{ background:var(--warn-soft); color:var(--warn); }
  .history-value{ font-family:'Oswald', sans-serif; font-weight:600; font-size:14.5px; color:var(--gold); white-space:nowrap; flex-shrink:0; }

  .empty-state{ padding:50px 16px; text-align:center; color:var(--text-faint); }
  .empty-state svg{ margin-bottom:14px; opacity:0.5; }
  .empty-state-title{ font-size:13.5px; color:var(--text-secondary); margin-bottom:4px; font-weight:600; }
  .empty-state-sub{ font-size:12px; }

  /* ---------- PERFIL ---------- */
  .profile-header{ display:flex; flex-direction:column; align-items:center; text-align:center; padding:14px 0 24px; }
  .profile-avatar{
    width:76px; height:76px; border-radius:50%;
    background:linear-gradient(135deg, var(--gold), #8a6d1f);
    display:flex; align-items:center; justify-content:center;
    color:var(--gold-dark); font-family:'Oswald', sans-serif; font-weight:700; font-size:24px;
    margin-bottom:12px;
    background-size:cover; background-position:center;
    position:relative;
  }
  .profile-avatar-wrap{ position:relative; display:inline-flex; margin-bottom:12px; }
  .profile-avatar-wrap .profile-avatar{ margin-bottom:0; }
  .avatar-upload-btn{
    position:absolute; right:-2px; bottom:-2px;
    width:28px; height:28px; border-radius:50%;
    background:var(--gold); color:var(--gold-dark);
    display:flex; align-items:center; justify-content:center;
    border:3px solid var(--bg-deep); cursor:pointer;
    transition:filter .15s ease, transform .1s ease;
  }
  .avatar-upload-btn:hover{ filter:brightness(1.08); }
  .avatar-upload-btn:active{ transform:scale(0.94); }
  .avatar-upload-input{ display:none; }
  .profile-name{ font-family:'Oswald', sans-serif; font-weight:600; font-size:18px; }
  .profile-email{ font-size:12.5px; color:var(--text-faint); margin-top:2px; }
  .profile-salon-chip{
    display:inline-flex; align-items:center; gap:6px; margin-top:10px;
    background:var(--gold-soft); color:var(--gold); font-size:11.5px; font-weight:600;
    padding:5px 12px; border-radius:20px;
  }

  .settings-card{ background:var(--bg-panel); border:1px solid var(--line); border-radius:var(--radius); padding:6px 18px; margin-bottom:16px; }
  .toggle-row{ display:flex; align-items:center; justify-content:space-between; gap:14px; padding:15px 0; border-top:1px solid var(--line); }
  .toggle-row:first-child{ border-top:none; }
  .toggle-row-label{ font-size:13.5px; font-weight:500; }
  .toggle-row-sub{ font-size:11.5px; color:var(--text-faint); margin-top:2px; }

  .switch{ position:relative; width:40px; height:22px; flex-shrink:0; }
  .switch input{ opacity:0; width:0; height:0; }
  .switch-track{ position:absolute; inset:0; background:var(--field-border); border-radius:20px; cursor:pointer; transition:background .15s ease; }
  .switch-track::before{ content:""; position:absolute; width:16px; height:16px; left:3px; top:3px; background:var(--text-primary); border-radius:50%; transition:transform .15s ease; }
  .switch input:checked + .switch-track{ background:var(--gold); }
  .switch input:checked + .switch-track::before{ transform:translateX(18px); background:var(--gold-dark); }

  .list-link-row{
    display:flex; align-items:center; gap:12px; padding:15px 0; border-top:1px solid var(--line);
    color:var(--text-primary); cursor:pointer;
  }
  .list-link-row:first-child{ border-top:none; }
  .list-link-row svg:first-child{ color:var(--text-faint); flex-shrink:0; }
  .list-link-row .chev{ margin-left:auto; color:var(--text-faint); }
  .list-link-row-text{ font-size:13.5px; font-weight:500; }

  .app-version{ text-align:center; font-size:11px; color:var(--text-faint); margin-top:22px; }

  /* ---------- MODAL ---------- */
  .modal-overlay{
    position:fixed; inset:0; background:rgba(0,0,0,0.65); display:none;
    align-items:flex-end; justify-content:center; z-index:150;
  }
  .modal-overlay.open{ display:flex; }
  .modal{
    background:var(--bg-panel); border:1px solid var(--line); border-top-left-radius:20px; border-top-right-radius:20px;
    width:100%; max-width:460px; padding:22px 22px calc(22px + env(safe-area-inset-bottom));
    animation:slideUp .2s ease;
  }
  @keyframes slideUp{ from{ transform:translateY(30px); opacity:0; } to{ transform:none; opacity:1; } }
  .modal-grip{ width:36px; height:4px; border-radius:4px; background:var(--field-border); margin:0 auto 18px; }
  .modal-icon{
    width:46px; height:46px; border-radius:12px; background:var(--danger-soft); color:var(--danger);
    display:flex; align-items:center; justify-content:center; margin-bottom:14px;
  }
  .modal-title{ font-family:'Oswald', sans-serif; font-weight:600; font-size:17px; margin-bottom:6px; }
  .modal-desc{ font-size:13px; color:var(--text-secondary); margin-bottom:20px; line-height:1.5; }

  /* ---------- TOAST ---------- */
  .toast{
    position:fixed; bottom:calc(var(--nav-h) + 14px); left:50%; transform:translateX(-50%) translateY(20px);
    background:var(--bg-elevated); border:1px solid var(--gold); color:var(--text-primary);
    padding:0; border-radius:12px; font-size:13px;
    opacity:0; pointer-events:none; transition:opacity .25s ease, transform .25s ease; z-index:200;
    box-shadow:0 10px 30px rgba(0,0,0,0.5); max-width:88vw; overflow:hidden;
  }
  .toast.show{ opacity:1; transform:translateX(-50%) translateY(0); pointer-events:auto; }
  .toast-body{ display:flex; align-items:center; gap:9px; padding:11px 8px 11px 16px; }
  .toast-icon{ color:var(--ok); flex-shrink:0; display:flex; }
  .toast-msg{ flex:1; min-width:0; }
  .toast-undo{
    flex-shrink:0; background:none; border:none; color:var(--gold); font-weight:700;
    font-size:12.5px; padding:8px 14px; cursor:pointer; white-space:nowrap;
    border-left:1px solid var(--field-border); margin-left:2px; display:none;
  }
  .toast-undo.show{ display:block; }
  .toast-undo:hover{ background:var(--gold-soft); }
  .toast-progress{ height:3px; background:var(--field-bg); display:none; }
  .toast-progress.show{ display:block; }
  .toast-progress-fill{ height:100%; background:var(--gold); width:100%; transform-origin:left; }
  .toast-progress-fill.animate{ animation:toastCountdown linear forwards; }
  @keyframes toastCountdown{ from{ transform:scaleX(1); } to{ transform:scaleX(0); } }

  /* ---------- BOTTOM NAV ---------- */
  .bottom-nav{
    position:sticky; bottom:0; left:0; right:0; z-index:40;
    background:rgba(20,20,22,0.95); backdrop-filter:blur(10px);
    border-top:1px solid var(--line);
    padding:9px 8px calc(9px + env(safe-area-inset-bottom));
    display:flex; justify-content:space-around;
    flex-shrink:0;
  }
  .nav-tab{
    display:flex; flex-direction:column; align-items:center; gap:4px;
    color:var(--text-faint); font-size:10.5px; font-weight:600;
    padding:6px 18px; border-radius:10px; cursor:pointer; transition:color .15s ease;
    position:relative;
  }
  .nav-tab.active{ color:var(--gold); }
  .nav-tab svg{ flex-shrink:0; }

  /* ============================================================
     DESKTOP (>= 1024px) — sidebar lateral + layout em grelha larga
     ============================================================ */
  @media (min-width:1024px){
    :root{ --sidebar-w:248px; }

    body{
      background:#050506;
      padding:0;
    }

    .app-shell{
      max-width:none;
      width:100%;
      min-height:100vh;
      min-height:100dvh;
      border-radius:0;
      border:none;
      overflow:hidden;
      box-shadow:none;
      display:grid;
      grid-template-columns:var(--sidebar-w) 1fr;
      grid-template-rows:var(--topbar-h) 1fr;
      grid-template-areas:
        "sidebar topbar"
        "sidebar content";
    }

    /* --- topbar ocupa a coluna de conteúdo --- */
    .topbar{
      grid-area:topbar;
      padding:0 36px;
      position:relative;
    }
    .brand-name{ max-width:260px; }

    /* --- conteúdo principal --- */
    .content{
      grid-area:content;
      padding:34px 40px 44px;
      max-width:1100px;
      width:100%;
      margin:0 auto;
      overflow-y:auto;
    }

    .greeting-title{ font-size:24px; }

    /* --- grelhas mais largas, adaptadas ao espaço disponível --- */
    .service-grid{
      grid-template-columns:repeat(auto-fill, minmax(168px, 1fr));
      gap:14px;
    }
    .service-card{ min-height:112px; }

    .payment-grid{
      display:grid;
      grid-template-columns:repeat(2, minmax(0,1fr));
      gap:14px;
    }

    #step-servico, #step-pagamento, #step-confirmar{ max-width:760px; }

    .kpi-mini-grid{ grid-template-columns:repeat(3, minmax(0,220px)); gap:14px; }

    .settings-card, .summary-card{ max-width:640px; }
    .profile-header{ align-items:flex-start; text-align:left; }
    #view-perfil .btn-danger-ghost{ max-width:280px; }

    /* --- barra lateral (substitui a bottom-nav) --- */
    .bottom-nav{
      grid-area:sidebar;
      position:sticky; top:0;
      height:100%;
      background:var(--bg-panel);
      border-top:none;
      border-right:1px solid var(--line);
      flex-direction:column;
      align-items:stretch;
      justify-content:flex-start;
      gap:6px;
      padding:20px 14px;
    }
    .bottom-nav::before{
      content:"MENU";
      font-size:10.5px; font-weight:700; letter-spacing:1px;
      color:var(--text-faint); padding:6px 14px 12px;
    }
    .nav-tab{
      flex-direction:row;
      justify-content:flex-start;
      align-items:center;
      gap:12px;
      width:100%;
      padding:12px 14px;
      border-radius:11px;
      font-size:13px;
    }
    .nav-tab:hover{ background:var(--field-bg); color:var(--text-primary); }
    .nav-tab.active{ background:var(--gold-soft); color:var(--gold); }

    /* --- toast já não precisa de reservar espaço da bottom-nav --- */
    .toast{ bottom:26px; }

    /* --- modal deixa de colar ao fundo do ecrã inteiro --- */
    .modal-overlay{ align-items:center; }
    .modal{
      max-width:400px;
      border-radius:20px;
      padding:26px 26px;
    }
  }
</style>
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
          <button class="btn btn-gold" onclick="confirmarAtendimento()">
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
            <div class="kpi-mini-value gold" id="kpiFaturadoHoje">{{ number_format($faturadoHoje, 0, ',', ' ') }} Kz</div>
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
        
        <!-- Container vazio para inserir novos atendimentos via JS -->
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

        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-danger-ghost">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            Sair da conta
          </button>
        </form>

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

@php
  // Prepara os dados para o JavaScript
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
      'TPA', 'Multicaixa (TPA)' => 'var(--info)',
      default => 'var(--warn)',
    },
    'bg' => match($p->name) {
      'Dinheiro físico' => 'var(--ok-soft)',
      'TPA', 'Multicaixa (TPA)' => 'var(--info-soft)',
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
  // ============================================================
  // DADOS DO BACKEND (injetados via Blade)
  // ============================================================
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const USER_ID = {{ $user->id }};

  const servicos = @json($servicosPayload);
  const metodosPagamento = @json($pagamentosPayload);

  // Estado inicial dos KPIs
  let faturadoHoje = {{ $faturadoHoje }};
  let qtdAtendimentos = {{ $qtdAtendimentosHoje }};

  // estado do fluxo de registo
  let servicoSelecionado = null;
  let pagamentoSelecionado = null;

  // ============================================================
  // HELPERS
  // ============================================================
  function kz(v){ return new Intl.NumberFormat('pt-AO', { maximumFractionDigits:0 }).format(v) + ' Kz'; }
  function horaAgora(){
    const d = new Date(); const pad = n => String(n).padStart(2,'0');
    return `${pad(d.getHours())}:${pad(d.getMinutes())}`;
  }

  // ============================================================
  // NAVEGAÇÃO ENTRE VIEWS
  // ============================================================
  function goToView(name){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    document.getElementById('view-' + name).classList.add('active');
    document.querySelectorAll('.nav-tab').forEach(t => t.classList.toggle('active', t.dataset.view === name));
    window.scrollTo({ top:0 });
  }

  // ============================================================
  // FLUXO DE REGISTO — 3 TOQUES
  // ============================================================
  function renderServiceGrid(){
    const grid = document.getElementById('serviceGrid');
    grid.innerHTML = servicos.map(s => `
      <div class="service-card" onclick="selecionarServico(${s.id})">
        <div class="service-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4 8.12 15.88M14.47 14.48 20 20M8.12 8.12 12 12"/></svg>
        </div>
        <div class="service-name">${s.nome}</div>
        <div class="service-price">${kz(s.preco)}</div>
      </div>
    `).join('');
  }

  function renderPaymentGrid(){
    const grid = document.getElementById('paymentGrid');
    grid.innerHTML = metodosPagamento.map(m => `
      <div class="payment-card" onclick="selecionarPagamento(${m.id})">
        <div class="payment-icon" style="background:${m.bg}; color:${m.color};">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">${m.icon}</svg>
        </div>
        <div>
          <div class="payment-name">${m.nome}</div>
          <div class="payment-sub">${m.sub}</div>
        </div>
        <div class="payment-check">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
      </div>
    `).join('');
  }

  function selecionarServico(id){
    servicoSelecionado = servicos.find(s => s.id === id);
    irParaStep(2);
  }

  function selecionarPagamento(id){
    pagamentoSelecionado = metodosPagamento.find(m => m.id === id);
    irParaStep(3);
  }

  function irParaStep(step){
    document.getElementById('step-servico').style.display = step === 1 ? 'block' : 'none';
    document.getElementById('step-pagamento').style.display = step === 2 ? 'block' : 'none';
    document.getElementById('step-confirmar').style.display = step === 3 ? 'block' : 'none';

    [1,2,3].forEach(n => {
      const seg = document.getElementById('seg' + n);
      seg.classList.toggle('done', n < step);
      seg.classList.toggle('current', n <= step);
      document.getElementById('lbl' + n).classList.toggle('active', n === step);
    });

    if(step === 2) renderPaymentGrid();
    if(step === 3){
      document.getElementById('sumServico').textContent = servicoSelecionado.nome;
      document.getElementById('sumPagamento').textContent = pagamentoSelecionado.nome;
      document.getElementById('sumHora').textContent = horaAgora();
      document.getElementById('sumValor').textContent = kz(servicoSelecionado.preco);
    }
    window.scrollTo({ top:0, behavior:'smooth' });
  }

  function voltarStep(step){ irParaStep(step); }

  function resetFluxo(){
    servicoSelecionado = null;
    pagamentoSelecionado = null;
    renderServiceGrid();
    irParaStep(1);
  }

  // ============================================================
  // CONFIRMAR ATENDIMENTO → POST ao Laravel
  // ============================================================
  function confirmarAtendimento(){
    if(!servicoSelecionado || !pagamentoSelecionado) return;

    const valor = servicoSelecionado.preco;
    const nomeServico = servicoSelecionado.nome;
    const nomePagamento = pagamentoSelecionado.nome;

    // Mostra overlay de sucesso imediatamente
    document.getElementById('successValor').textContent = kz(valor);
    document.getElementById('successSub').textContent = `${nomeServico} · ${nomePagamento}`;
    document.getElementById('successOverlay').classList.add('show');

    // POST ao backend
    fetch(`/users/${USER_ID}/atendimentos`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        service_id: servicoSelecionado.id,
        pagamento_id: pagamentoSelecionado.id,
        valor: valor,
      })
    })
    .then(async res => {
      if(!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || 'Erro ao registar');
      }
      return res.json();
    })
    .then((novoAtendimento) => {
      setTimeout(() => {
        document.getElementById('successOverlay').classList.remove('show');
        resetFluxo();
        showToast(`Registado: ${nomeServico} · ${kz(valor)}`, {
          duration: 6000,
          undoAction: () => desfazerAtendimento(novoAtendimento.id, valor)
        });
        
        // Atualiza os KPIs e a lista visualmente
        updateDiaStats(valor);
        prependHistoryItem(novoAtendimento);
      }, 1400);
    })
    .catch(error => {
      document.getElementById('successOverlay').classList.remove('show');
      showToast(error.message || 'Erro ao guardar o atendimento. Tenta novamente.');
    });
  }

  // Função auxiliar para adicionar o item novo na lista visualmente sem reload
  function prependHistoryItem(atendimento) {
    // Mapeia ID do pagamento para classes/nomes visuais
    const pagId = atendimento.pagamento_id; 
    const metodo = metodosPagamento.find(m => m.id == pagId);
    
    let badgeClass = 'transferencia';
    let badgeLabel = 'Transferência';
    
    if(metodo) {
       if(metodo.nome.includes('Dinheiro')) { badgeClass = 'dinheiro'; badgeLabel = 'Dinheiro'; }
       else if(metodo.nome.includes('TPA') || metodo.nome.includes('Multicaixa')) { badgeClass = 'multicaixa'; badgeLabel = 'Multicaixa'; }
    }

    const hora = new Date().toLocaleTimeString('pt-AO', {hour: '2-digit', minute:'2-digit'});
    
    const html = `
      <div class="history-item" id="atend-${atendimento.id}" style="animation: fadeIn 0.3s ease">
        <div class="history-time">${hora}</div>
        <div class="history-divider"></div>
        <div class="history-info">
          <div class="history-service">${atendimento.service.name}</div>
          <div class="history-meta">
            <span class="pay-badge ${badgeClass}">${badgeLabel}</span>
          </div>
        </div>
        <div class="history-value">${kz(atendimento.valor)}</div>
      </div>
    `;

    const container = document.getElementById('newHistoryItems');
    // Se houver empty state, esconde
    const emptyState = document.getElementById('historyEmpty');
    if(emptyState) emptyState.style.display = 'none';
    
    container.insertAdjacentHTML('afterbegin', html);
  }

  // Actualiza os KPIs do Meu Dia em memória (sem reload)
  function updateDiaStats(novoValor){
    faturadoHoje += novoValor;
    qtdAtendimentos++;
    document.getElementById('kpiFaturadoHoje').textContent = kz(faturadoHoje);
    document.getElementById('kpiTotalAtend').textContent = qtdAtendimentos;
    document.getElementById('kpiTicketMedio').textContent = kz(Math.round(faturadoHoje / qtdAtendimentos));
  }

  function desfazerAtendimento(id, valor) {
    // Remove visualmente
    const el = document.getElementById(`atend-${id}`);
    if(el) el.remove();

    // Reverte KPIs
    faturadoHoje -= valor;
    qtdAtendimentos--;
    document.getElementById('kpiFaturadoHoje').textContent = kz(faturadoHoje);
    document.getElementById('kpiTotalAtend').textContent = qtdAtendimentos;
    
    const ticket = qtdAtendimentos > 0 ? Math.round(faturadoHoje / qtdAtendimentos) : 0;
    document.getElementById('kpiTicketMedio').textContent = qtdAtendimentos > 0 ? kz(ticket) : '—';

    // Se a lista ficar vazia, mostra empty state
    const list = document.getElementById('historyList');
    const newItems = document.getElementById('newHistoryItems');
    // Verificação simplificada: se não tem itens no historyList (blade) E nem no newItems (JS)
    if(list.children.length === 0 && newItems.children.length === 0) {
       const emptyState = document.getElementById('historyEmpty');
       if(emptyState) emptyState.style.display = 'block';
    }
    
    showToast('Atendimento anulado.');
    
    // Opcional: chamar rota DELETE no backend aqui se necessário
  }

  // ============================================================
  // PERFIL
  // ============================================================
  function openChangePassword(){
    showToast('Contacta o gestor do salão para alterar a tua palavra-passe.');
  }
  
  function closeModal(id){ document.getElementById(id).classList.remove('open'); }
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => { if(e.target === overlay) overlay.classList.remove('open'); });
  });

  // Upload de Avatar (apenas visual/local, requer rota específica para salvar)
  function handleAvatarUpload(event){
    const file = event.target.files && event.target.files[0];
    if(!file) return;
    if(!file.type.startsWith('image/')){ showToast('Escolhe um ficheiro de imagem válido.'); return; }
    
    const reader = new FileReader();
    reader.onload = e => {
      const dataUrl = e.target.result;
      [document.getElementById('profileAvatar'), document.getElementById('userAvatar')].forEach(el => {
        el.style.backgroundImage = `url('${dataUrl}')`;
        el.textContent = '';
      });
      showToast('Foto atualizada localmente.');
    };
    reader.readAsDataURL(file);
  }

  // ============================================================
  // TOAST
  // ============================================================
  let toastTimer;
  let pendingUndo = null;

  function showToast(msg, opts = {}){
    const toast = document.getElementById('toast');
    const undoBtn = document.getElementById('toastUndoBtn');
    const progress = document.getElementById('toastProgress');
    const fill = document.getElementById('toastProgressFill');
    const duration = opts.duration || 3000;
    pendingUndo = null; clearTimeout(toastTimer);
    document.getElementById('toastMsg').textContent = msg;
    if(opts.undoAction){
      undoBtn.classList.add('show'); progress.classList.add('show');
      fill.classList.remove('animate'); void fill.offsetWidth;
      fill.style.animationDuration = duration + 'ms'; fill.classList.add('animate');
      pendingUndo = opts.undoAction;
    } else {
      undoBtn.classList.remove('show'); progress.classList.remove('show'); fill.classList.remove('animate');
    }
    toast.classList.add('show');
    toastTimer = setTimeout(() => { toast.classList.remove('show'); pendingUndo = null; }, duration);
  }

  function handleToastUndo(){
    if(pendingUndo){ const action = pendingUndo; pendingUndo = null; clearTimeout(toastTimer); document.getElementById('toast').classList.remove('show'); action(); }
  }

  // ============================================================
  // INIT
  // ============================================================
  (function init(){
    const hoje = new Date();
    document.getElementById('dataHoje').textContent = hoje.toLocaleDateString('pt-AO', { weekday:'long', day:'2-digit', month:'long' });
    renderServiceGrid();
    irParaStep(1);
  })();

  // Feedback quando sessão flash existir (ex: após redirect)
  @if(session('success'))
    showToast('{{ session("success") }}');
  @endif
</script>

</body>
</html>