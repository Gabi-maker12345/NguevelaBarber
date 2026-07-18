<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nguevela · Admin Master</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/dashboard.css'])
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
    --radius:10px;
  }

  *{ box-sizing:border-box; margin:0; padding:0; }

  button:focus-visible, a:focus-visible, input:focus-visible, select:focus-visible{
    outline:2px solid var(--gold);
    outline-offset:2px;
  }

  html,body{
    min-height:100%;
    font-family:'Inter', sans-serif;
    background:var(--bg-deep);
    color:var(--text-primary);
  }

  ::-webkit-scrollbar{ width:8px; height:8px; }
  ::-webkit-scrollbar-thumb{ background:var(--field-border); border-radius:8px; }

  /* ============ LAYOUT ============ */
  .app{
    display:flex;
    min-height:100vh;
  }

  /* ---------- SIDEBAR ---------- */
  .sidebar{
    width:248px;
    flex-shrink:0;
    background:var(--bg-panel);
    border-right:1px solid var(--line);
    display:flex;
    flex-direction:column;
    padding:24px 16px;
    position:sticky;
    top:0;
    height:100vh;
  }

  .brand{
    display:flex;
    align-items:center;
    gap:10px;
    padding:0 8px 24px;
    margin-bottom:8px;
    border-bottom:1px solid var(--line);
  }

  .brand-mark{
    width:34px; height:34px;
    border-radius:8px;
    background:var(--gold);
    color:var(--gold-dark);
    display:flex; align-items:center; justify-content:center;
    font-family:'Oswald', sans-serif;
    font-weight:700;
    font-size:15px;
    flex-shrink:0;
  }

  .brand-text{ line-height:1.25; }
  .brand-name{ font-family:'Oswald', sans-serif; font-weight:600; font-size:15px; }
  .brand-role{ font-size:11px; letter-spacing:0.8px; color:var(--gold); }

  .nav-group-label{
    font-size:11px;
    letter-spacing:1px;
    color:var(--text-faint);
    text-transform:uppercase;
    padding:18px 10px 8px;
  }

  .nav-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 12px;
    border-radius:8px;
    color:var(--text-secondary);
    font-size:14px;
    font-weight:500;
    cursor:pointer;
    transition:background .15s ease, color .15s ease;
    -webkit-tap-highlight-color:transparent;
    text-decoration: none;
  }

  .nav-item svg{ flex-shrink:0; opacity:0.85; }

  .nav-item:hover{ background:var(--bg-elevated); color:var(--text-primary); }

  .nav-item.active{
    background:var(--gold-soft);
    color:var(--gold);
  }
  .nav-item.active svg{ opacity:1; }

  .sidebar-footer{
    margin-top:auto;
    padding-top:16px;
    border-top:1px solid var(--line);
  }

  .admin-chip{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px;
    border-radius:8px;
  }

  .admin-avatar{
    width:34px; height:34px;
    border-radius:50%;
    background:linear-gradient(135deg, var(--gold), #8a6d1f);
    display:flex; align-items:center; justify-content:center;
    font-weight:700;
    color:var(--gold-dark);
    font-size:13px;
    flex-shrink:0;
  }

  .admin-name{ font-size:13px; font-weight:600; }
  .admin-email{ font-size:11.5px; color:var(--text-faint); }

  /* ---------- MAIN ---------- */
  .main{
    flex:1;
    min-width:0;
    padding:28px 34px 80px;
  }

  .topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    margin-bottom:26px;
    flex-wrap:wrap;
  }

  .page-title{ font-family:'Oswald', sans-serif; font-weight:600; font-size:26px; }
  .page-sub{ font-size:13.5px; color:var(--text-secondary); margin-top:4px; }

  .topbar-actions{ display:flex; align-items:center; gap:12px; }

  .search-field{
    display:flex; align-items:center; gap:8px;
    background:var(--field-bg);
    border:1px solid var(--field-border);
    border-radius:8px;
    padding:9px 12px;
    width:240px;
  }
  .search-field input{
    background:none; border:none; outline:none;
    color:var(--text-primary); font-size:13.5px; width:100%;
    font-family:'Inter', sans-serif;
  }
  .search-field input::placeholder{ color:var(--text-faint); }
  .search-field svg{ color:var(--text-faint); flex-shrink:0; }

  .btn{
    display:inline-flex; align-items:center; gap:8px;
    font-family:'Inter', sans-serif;
    font-weight:600;
    font-size:13.5px;
    padding:10px 16px;
    border-radius:8px;
    border:none;
    cursor:pointer;
    transition:filter .15s ease, background .15s ease;
    white-space:nowrap;
    text-decoration: none;
  }
  .btn-gold{ background:var(--gold); color:var(--gold-dark); }
  .btn-gold:hover{ filter:brightness(1.08); }
  .btn-ghost{ background:var(--bg-elevated); color:var(--text-primary); border:1px solid var(--field-border); }
  .btn-ghost:hover{ border-color:var(--gold); }
  .btn-sm{ padding:7px 12px; font-size:12.5px; }
  .btn-danger-ghost{ background:transparent; color:var(--danger); border:1px solid var(--danger); }
  .btn-danger-ghost:hover{ background:var(--danger-soft); }

  /* ---------- KPI CARDS ---------- */
  .kpi-grid{
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:16px;
    margin-bottom:28px;
  }

  .kpi-card{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:18px 20px;
  }

  .kpi-top{ display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
  .kpi-icon{
    width:34px; height:34px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
  }
  .kpi-label{ font-size:12.5px; color:var(--text-secondary); }
  .kpi-value{ font-family:'Oswald', sans-serif; font-size:26px; font-weight:600; }
  .kpi-delta{ font-size:12px; margin-top:6px; color:var(--text-faint); }
  .kpi-delta.up{ color:var(--ok); }
  .kpi-delta.warn{ color:var(--warn); }

  /* ---------- FILTER BAR ---------- */
  .filter-bar{
    display:flex; align-items:center; gap:10px;
    margin-bottom:16px;
    flex-wrap:wrap;
  }

  .filter-chip{
    padding:7px 14px;
    border-radius:20px;
    font-size:12.5px;
    font-weight:500;
    background:var(--bg-elevated);
    border:1px solid var(--field-border);
    color:var(--text-secondary);
    cursor:pointer;
    transition:all .15s ease;
  }
  .filter-chip.active{
    background:var(--gold-soft);
    border-color:var(--gold);
    color:var(--gold);
  }

  /* ---------- VIEW SWITCHING ---------- */
  .view{ display:none; }
  .view.active{ display:block; }

  /* ---------- SUMMARY CARDS (billing / requests) ---------- */
  .summary-row{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:16px;
    margin-bottom:24px;
  }
  .summary-card{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:18px 20px;
  }
  .summary-label{ font-size:12.5px; color:var(--text-secondary); margin-bottom:8px; }
  .summary-value{ font-family:'Oswald', sans-serif; font-size:24px; font-weight:600; }
  .summary-note{ font-size:12px; color:var(--text-faint); margin-top:6px; }

  /* ---------- REQUEST CARDS ---------- */
  .request-card{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    padding:16px 20px;
    border-bottom:1px solid var(--line);
    flex-wrap:wrap;
  }
  .request-card:last-child{ border-bottom:none; }
  .request-main{ display:flex; align-items:center; gap:14px; min-width:0; }
  .request-icon{
    width:40px; height:40px; border-radius:9px;
    background:var(--gold-soft); color:var(--gold);
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
  }
  .request-title{ font-weight:600; font-size:13.5px; }
  .request-sub{ font-size:12px; color:var(--text-faint); margin-top:2px; }
  .request-value{ font-size:13px; color:var(--text-secondary); white-space:nowrap; }
  .request-actions{ display:flex; gap:8px; flex-shrink:0; }
  .request-status{
    display:inline-flex; align-items:center; gap:6px;
    padding:5px 10px; border-radius:20px;
    font-size:11.5px; font-weight:600;
  }

  /* ---------- SETTINGS FORM ---------- */
  .settings-card{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:24px;
    max-width:560px;
    margin-bottom:20px;
  }
  .settings-card-title{ font-family:'Oswald', sans-serif; font-weight:600; font-size:16px; margin-bottom:4px; }
  .settings-card-desc{ font-size:12.5px; color:var(--text-secondary); margin-bottom:18px; }

  .toggle-row{
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 0;
    border-top:1px solid var(--line);
  }
  .toggle-row:first-of-type{ border-top:none; }
  .toggle-row-label{ font-size:13.5px; font-weight:500; }
  .toggle-row-sub{ font-size:12px; color:var(--text-faint); margin-top:2px; }

  .switch{ position:relative; width:40px; height:22px; flex-shrink:0; }
  .switch input{ opacity:0; width:0; height:0; }
  .switch-track{
    position:absolute; inset:0;
    background:var(--field-border);
    border-radius:20px;
    cursor:pointer;
    transition:background .15s ease;
  }
  .switch-track::before{
    content:"";
    position:absolute;
    width:16px; height:16px;
    left:3px; top:3px;
    background:var(--text-primary);
    border-radius:50%;
    transition:transform .15s ease;
  }
  .switch input:checked + .switch-track{ background:var(--gold); }
  .switch input:checked + .switch-track::before{ transform:translateX(18px); background:var(--gold-dark); }

  /* ---------- SALON TABLE (desktop) ---------- */
  .panel{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:var(--radius);
    overflow:hidden;
  }

  .table-wrap{ overflow-x:auto; }

  table{ width:100%; border-collapse:collapse; min-width:820px; }

  thead th{
    text-align:left;
    font-size:11.5px;
    letter-spacing:0.6px;
    text-transform:uppercase;
    color:var(--text-faint);
    font-weight:600;
    padding:14px 18px;
    border-bottom:1px solid var(--line);
  }

  tbody td{
    padding:14px 18px;
    font-size:13.5px;
    border-bottom:1px solid var(--line);
    vertical-align:middle;
  }

  tbody tr:last-child td{ border-bottom:none; }
  tbody tr:hover{ background:var(--bg-elevated); }

  .salon-cell{ display:flex; align-items:center; gap:12px; }
  .salon-logo{
    width:38px; height:38px; border-radius:8px;
    background:var(--gold-soft);
    color:var(--gold);
    display:flex; align-items:center; justify-content:center;
    font-family:'Oswald', sans-serif; font-weight:600; font-size:14px;
    flex-shrink:0;
  }
  .salon-name{ font-weight:600; font-size:13.5px; }
  .salon-owner{ font-size:12px; color:var(--text-faint); }

  .status-badge{
    display:inline-flex; align-items:center; gap:6px;
    padding:5px 10px;
    border-radius:20px;
    font-size:11.5px;
    font-weight:600;
  }
  .status-badge.ativo{ background:var(--ok-soft); color:var(--ok); }
  .status-badge.expirar{ background:var(--warn-soft); color:var(--warn); }
  .status-badge.suspenso{ background:var(--danger-soft); color:var(--danger); }
  .status-dot{ width:6px; height:6px; border-radius:50%; background:currentColor; }

  .row-actions{ display:flex; align-items:center; gap:8px; }

  .icon-btn{
    width:32px; height:32px;
    display:flex; align-items:center; justify-content:center;
    border-radius:7px;
    background:var(--bg-elevated);
    border:1px solid var(--field-border);
    color:var(--text-secondary);
    cursor:pointer;
    transition:all .15s ease;
  }
  .icon-btn:hover{ color:var(--text-primary); border-color:var(--gold); }
  .icon-btn.danger:hover{ color:var(--danger); border-color:var(--danger); }

  .plan-tag{ font-size:12px; color:var(--text-secondary); }
  .muted{ color:var(--text-faint); font-size:12px; }

  .empty-state{
    padding:60px 20px;
    text-align:center;
    color:var(--text-faint);
  }
  .empty-state svg{ margin-bottom:14px; opacity:0.5; }
  .empty-state-title{ font-size:14px; color:var(--text-secondary); margin-bottom:4px; }

  /* ---------- WIZARD MODAL (nova empresa) ---------- */
  .plan-badge{
    display:flex; align-items:center; justify-content:space-between;
    background:var(--gold-soft);
    border:1px solid rgba(212,175,55,0.35);
    border-radius:8px;
    padding:10px 14px;
    margin-bottom:20px;
  }
  .plan-badge-label{ font-size:12px; color:var(--text-secondary); }
  .plan-badge-value{ font-family:'Oswald', sans-serif; font-weight:600; font-size:14.5px; color:var(--gold); }

  .step-dots{ display:flex; align-items:center; gap:6px; margin-bottom:4px; }
  .step-dot{ width:22px; height:4px; border-radius:3px; background:var(--field-border); transition:background .15s ease; }
  .step-dot.done{ background:var(--gold); }
  .step-dot.current{ background:var(--gold); }
  .step-label{ font-size:11.5px; color:var(--text-faint); margin-bottom:18px; }

  .wizard-step{ display:none; }
  .wizard-step.active{ display:block; }

  .section-label{
    font-size:11px; letter-spacing:0.8px; text-transform:uppercase;
    color:var(--gold); font-weight:600;
    margin:4px 0 12px;
    display:flex; align-items:center; gap:8px;
  }
  .section-label::after{ content:""; flex:1; height:1px; background:var(--line); }

  .field-with-action{ position:relative; }
  .field-inline-actions{
    position:absolute; right:6px; top:50%; transform:translateY(-50%);
    display:flex; gap:4px;
  }
  .field-inline-action{
    background:none; border:none; color:var(--gold);
    font-size:11.5px; font-weight:600; cursor:pointer; padding:4px 6px;
    white-space:nowrap;
  }
  .field-inline-action:hover{ text-decoration:underline; }

  .funcionario-list{ display:flex; flex-direction:column; gap:10px; margin-bottom:14px; }
  .funcionario-row{
    display:flex;
    flex-direction:column;
    gap:8px;
    background:var(--bg-elevated);
    border:1px solid var(--field-border);
    border-radius:8px;
    padding:10px;
  }
  .funcionario-row-top{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:8px;
  }
  .funcionario-row-bottom{
    display:flex;
    align-items:center;
    gap:8px;
  }
  .funcionario-row-bottom .field-with-action{ flex:1; }
  .funcionario-row .field-input{ padding:8px 10px; font-size:13px; }
  .funcionario-remove{
    width:34px; height:34px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    border-radius:7px;
    background:transparent; border:1px solid var(--field-border);
    color:var(--text-faint); cursor:pointer;
  }
  .funcionario-remove:hover{ color:var(--danger); border-color:var(--danger); }

  .add-funcionario-btn{
    display:flex; align-items:center; justify-content:center; gap:8px;
    width:100%;
    padding:11px;
    border-radius:8px;
    border:1px dashed var(--field-border);
    background:none;
    color:var(--text-secondary);
    font-size:13px; font-weight:600;
    cursor:pointer;
    transition:all .15s ease;
  }
  .add-funcionario-btn:hover{ border-color:var(--gold); color:var(--gold); }

  .funcionario-empty{
    text-align:center;
    padding:22px 12px;
    color:var(--text-faint);
    font-size:12.5px;
    border:1px dashed var(--field-border);
    border-radius:8px;
    margin-bottom:14px;
  }

  .skip-note{ font-size:12px; color:var(--text-faint); margin-top:10px; }

  /* ---------- MODAL ---------- */
  .modal-overlay{
    position:fixed; inset:0;
    background:rgba(0,0,0,0.6);
    display:none;
    align-items:center; justify-content:center;
    z-index:100;
    padding:20px;
  }
  .modal-overlay.open{ display:flex; }

  .modal{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:16px;
    width:100%;
    max-width:480px;
    max-height:90vh;
    overflow-y:auto;
    padding:26px 26px 24px;
  }

  .modal-header{
    display:flex; align-items:flex-start; justify-content:space-between;
    margin-bottom:6px;
  }
  .modal-title{ font-family:'Oswald', sans-serif; font-weight:600; font-size:19px; }
  .modal-desc{ font-size:13px; color:var(--text-secondary); margin-bottom:22px; }
  .modal-close{
    width:30px; height:30px; border-radius:7px;
    display:flex; align-items:center; justify-content:center;
    background:var(--bg-elevated); border:1px solid var(--field-border);
    color:var(--text-secondary); cursor:pointer; flex-shrink:0;
  }

  .form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:14px; }
  .form-grid .full{ grid-column:1 / -1; }

  .field-label{
    font-size:12px; font-weight:500;
    color:var(--text-secondary);
    margin-bottom:6px;
    display:block;
  }

  .field-input, .field-select{
    width:100%;
    background:var(--field-bg);
    border:1px solid var(--field-border);
    border-radius:8px;
    padding:10px 12px;
    font-size:13.5px;
    color:var(--text-primary);
    font-family:'Inter', sans-serif;
    outline:none;
    transition:border-color .15s ease;
  }
  .field-input:focus, .field-select:focus{ border-color:var(--gold); }
  .field-input::placeholder{ color:var(--text-faint); }

  .field-block{ margin-bottom:14px; }

  .modal-actions{
    display:flex; justify-content:flex-end; gap:10px;
    margin-top:22px;
    padding-top:18px;
    border-top:1px solid var(--line);
  }

  /* confirm modal */
  .confirm-icon{
    width:46px; height:46px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    margin-bottom:14px;
  }

  /* ---------- TOAST ---------- */
  .toast{
    position:fixed;
    bottom:24px; left:50%;
    transform:translateX(-50%) translateY(20px);
    background:var(--bg-elevated);
    border:1px solid var(--gold);
    color:var(--text-primary);
    padding:12px 20px;
    border-radius:10px;
    font-size:13.5px;
    display:flex; align-items:center; gap:10px;
    opacity:0;
    pointer-events:none;
    transition:opacity .25s ease, transform .25s ease;
    z-index:200;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
  }
  .toast.show{ opacity:1; transform:translateX(-50%) translateY(0); pointer-events:auto; }

  /* ---------- MOBILE BOTTOM NAV ---------- */
  .mobile-nav{
    display:none;
    position:fixed;
    bottom:0; left:0; right:0;
    background:var(--bg-panel);
    border-top:1px solid var(--line);
    padding:8px 6px calc(8px + env(safe-area-inset-bottom));
    z-index:60;
  }
  .mobile-nav-inner{ display:flex; justify-content:space-around; }
  .mobile-nav-item{
    display:flex; flex-direction:column; align-items:center; gap:3px;
    color:var(--text-faint);
    font-size:10.5px;
    padding:4px 10px;
    cursor:pointer;
  }
  .mobile-nav-item.active{ color:var(--gold); }

  .mobile-fab{
    display:none;
    position:fixed;
    right:18px; bottom:78px;
    width:52px; height:52px;
    border-radius:50%;
    background:var(--gold);
    color:var(--gold-dark);
    align-items:center; justify-content:center;
    border:none;
    box-shadow:0 10px 30px rgba(212,175,55,0.35);
    z-index:60;
    cursor:pointer;
  }

  /* ============ MOBILE LAYOUT ============ */
  @media (max-width: 900px){
    .kpi-grid{ grid-template-columns:1fr 1fr; }
  }

  @media (max-width: 768px){
    .sidebar{ display:none; }

    .app{ flex-direction:column; }

    .main{
      padding:18px 16px 100px;
      width:100%;
      max-width:100%;
      overflow-x:hidden;
    }

    .topbar{ flex-direction:column; align-items:stretch; gap:14px; }
    .topbar-actions{ width:100%; }
    .search-field{ width:100%; }
    .topbar .btn-gold.desktop-only{ display:none; }

    .kpi-grid{
      grid-template-columns:1fr 1fr;
      gap:10px;
    }
    .kpi-card{ padding:14px; }
    .kpi-value{ font-size:21px; }

    .summary-row{ grid-template-columns:1fr; gap:10px; }

    .request-card{ padding:14px; }
    .request-actions{ width:100%; }
    .request-actions .btn{ flex:1; justify-content:center; }

    .settings-card{ max-width:100%; padding:18px; }

    .filter-bar{
      flex-wrap:nowrap;
      overflow-x:auto;
      padding-bottom:4px;
    }

    /* table -> stacked cards on mobile */
    .table-wrap{ overflow-x:visible; }
    table, thead{ display:none; }
    tbody{ display:block; }
    tbody tr{
      display:block;
      padding:16px;
      border-bottom:1px solid var(--line);
    }
    tbody td{
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:6px 0;
      border-bottom:none;
      font-size:13px;
      max-width:100%;
      overflow:hidden;
    }
    tbody td::before{
      content:attr(data-label);
      font-size:11px;
      color:var(--text-faint);
      flex-shrink:0;
      margin-right:12px;
    }
    tbody td.cell-salon{ display:block; }
    tbody td.cell-salon::before{ display:none; }
    tbody td.cell-salon .salon-cell{ margin-bottom:10px; }
    tbody td.cell-actions{ display:block; }
    tbody td.cell-actions::before{ display:none; }
    .row-actions{ justify-content:flex-start; margin-top:8px; flex-wrap:wrap; }

    .form-grid{ grid-template-columns:1fr; }
    .funcionario-row-top{ grid-template-columns:1fr; }

    .mobile-nav{ display:block; }
    .mobile-fab{ display:flex; }

    .modal{ max-width:100%; border-radius:16px; }
  }
</style>
</head>
<body>

<div class="app">

  <!-- ============ SIDEBAR (desktop) ============ -->
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">NB</div>
      <div class="brand-text">
        <div class="brand-name">Nguevela</div>
        <div class="brand-role">ADMIN MASTER</div>
      </div>
    </div>

    <div class="nav-group-label">Plataforma</div>
    <div class="nav-item active" data-view="overview" onclick="switchView('overview')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
      Visão geral
    </div>
    <div class="nav-item" data-view="billing" onclick="switchView('billing')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
      Faturação SaaS
    </div>
    <div class="nav-item" data-view="settings" onclick="switchView('settings')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Configurações
    </div>

    <div class="sidebar-footer">
      <div class="admin-chip">
        <div class="admin-avatar">{{ substr(Auth::user()->name ?? 'AM', 0, 2) }}</div>
        <div>
          <div class="admin-name">{{ Auth::user()->name ?? 'Admin' }}</div>
          <div class="admin-email">{{ Auth::user()->email ?? 'master@nguevela.ao' }}</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ============ MAIN ============ -->
  <main class="main">

    <div class="topbar">
      <div>
        <div class="page-title" id="pageTitle">Salões registados</div>
        <div class="page-sub" id="pageSub">Gere todos os estabelecimentos, mensalidades e acessos da plataforma.</div>
      </div>
      <div class="topbar-actions" id="topbarActions">
        <div class="search-field">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input id="searchInput" type="text" placeholder="Procurar salão ou dono..." oninput="filterTable()">
        </div>
        <button class="btn btn-gold desktop-only" onclick="openCreateModal()">
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
          <div class="kpi-icon" style="background:var(--gold-soft); color:var(--gold);">
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
          <div class="kpi-icon" style="background:var(--warn-soft); color:var(--warn);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          </div>
        </div>
        <div class="kpi-value">{{ $saloesAExpirar }}</div>
        <div class="kpi-delta warn">requer atenção</div>
      </div>

      <div class="kpi-card">
        <div class="kpi-top">
          <div class="kpi-label">Receita mensal (Kz)</div>
          <div class="kpi-icon" style="background:var(--gold-soft); color:var(--gold);">
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

    <!-- ---------- TABLE ---------- -->
    <div class="panel">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Salão</th>
              <th>Plano</th>
              <th>Expira em</th>
              <th>Status</th>
              <th>Equipa</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="salonsBody">
            @foreach($barbearias as $barbearia)
            @php
                // Lógica de status baseada nos dados reais
                $diasRestantes = $barbearia->days_until_expiration;
                $proximaCobranca = $barbearia->next_billing_date;
                $statusClass = 'ativo';
                $statusText = 'Ativo';
                
                if (!$barbearia->isactive) {
                    $statusClass = 'suspenso';
                    $statusText = 'Suspenso';
                } elseif ($diasRestantes !== null && $diasRestantes <= 5 && $diasRestantes >= 0) {
                    $statusClass = 'expirar';
                    $statusText = "Expira em {$diasRestantes}d";
                } elseif ($diasRestantes !== null && $diasRestantes < 0) {
                     $statusClass = 'suspenso';
                     $statusText = 'Expirado';
                }
            @endphp
            <tr class="salon-row" 
                data-status="{{ $statusClass }}" 
                data-name="{{ strtolower($barbearia->name) }}" 
                data-owner="{{ strtolower($barbearia->gestor) }}">
                
              <td class="cell-salon">
                <div class="salon-cell">
                  <div class="salon-logo">{{ strtoupper(substr($barbearia->name, 0, 2)) }}</div>
                  <div>
                    <div class="salon-name">{{ $barbearia->name }}</div>
                    <div class="salon-owner">{{ $barbearia->gestor }} · {{ $barbearia->municipio }}</div>
                  </div>
                </div>
              </td>
              <td data-label="Plano"><span class="plan-tag">Mensal · {{ number_format($barbearia->plano, 0, ',', '.') }} Kz</span></td>
              <td data-label="Expira em">{{ $proximaCobranca->format('d M Y') }}</td>
              <td data-label="Status"><span class="status-badge {{ $statusClass }}"><span class="status-dot"></span>{{ $statusText }}</span></td>
              <td data-label="Equipa">{{ $barbearia->users_count }} pessoas</td>
              <td class="cell-actions">
                <div class="row-actions">
                  {{-- Botão Editar abre modal --}}
                  <button class="icon-btn" title="Ver detalhes" onclick='openDetailModal(@json($barbearia))'>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  
                  {{-- Formulário de exclusão --}}
                  <form action="{{ route('barbearias.destroy', $barbearia->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="icon-btn danger" title="Remover" onclick="confirmDeleteBarbearia(this)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
            
            @if($barbearias->isEmpty())
            <tr>
                <td colspan="6" class="empty-state">
                    <div class="empty-state-title">Nenhum salão encontrado</div>
                    <div>Comece adicionando uma nova barbearia.</div>
                </td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>

    </section>

    <!-- ============ VIEW: FATURAÇÃO SAAS ============ -->
    <section class="view" id="view-billing">
      <div class="summary-row">
        <div class="summary-card">
          <div class="summary-label">Receita mensal recorrente</div>
          <div class="summary-value">{{ number_format($receitaMensal, 0, ',', '.') }} Kz</div>
          <div class="summary-note">soma dos planos de salões não suspensos</div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Em atraso / suspensos</div>
          <div class="summary-value">{{ $saloesEmAtraso }}</div>
          <div class="summary-note">{{ number_format($valorEmRisco, 0, ',', '.') }} Kz em risco</div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Ticket médio por salão</div>
          <div class="summary-value">{{ number_format($ticketMedio, 0, ',', '.') }} Kz</div>
          <div class="summary-note">baseado no plano contratado</div>
        </div>
      </div>

      <div class="panel" style="margin-bottom:20px;">
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Plano</th><th>Preço</th><th>Salões ativos</th><th>Receita do plano</th></tr>
            </thead>
            <tbody id="billingPlanBody">
                {{-- Como o controller envia a coleção completa, agrupamos aqui via Blade --}}
                @php
                    $planosAgrupados = $barbearias->where('isactive', true)->groupBy('plano');
                @endphp
                @foreach($planosAgrupados as $valorPlano => $saloesDoPlano)
                <tr>
                    <td data-label="Plano"><strong>Mensal</strong></td>
                    <td data-label="Preço">{{ number_format($valorPlano, 0, ',', '.') }} Kz</td>
                    <td data-label="Salões ativos">{{ $saloesDoPlano->count() }}</td>
                    <td data-label="Receita do plano">{{ number_format($valorPlano * $saloesDoPlano->count(), 0, ',', '.') }} Kz</td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="page-sub" style="margin-bottom:10px;">Mensalidades por salão</div>
      <div class="panel">
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Salão</th><th>Plano</th><th>Valor</th><th>Próxima cobrança</th><th>Estado</th></tr>
            </thead>
            <tbody id="billingSalonBody">
                @foreach($barbearias as $barbearia)
                @php
                    $diasRestantes = $barbearia->days_until_expiration;
                    $proximaCobranca = $barbearia->next_billing_date;
                    $statusBilling = $barbearia->isactive ? 'ativo' : 'suspenso';
                    $textoStatus = $barbearia->isactive ? 'Em dia' : 'Em atraso';
                    if($barbearia->isactive && $diasRestantes <= 5) {
                        $statusBilling = 'expirar';
                        $textoStatus = "Vence em {$diasRestantes}d";
                    }
                @endphp
                <tr>
                    <td data-label="Salão">{{ $barbearia->name }}</td>
                    <td data-label="Plano">Mensal</td>
                    <td data-label="Valor">{{ number_format($barbearia->plano, 0, ',', '.') }} Kz</td>
                    <td data-label="Próxima cobrança">{{ $proximaCobranca->format('d M Y') }}</td>
                    <td data-label="Estado"><span class="status-badge {{ $statusBilling }}"><span class="status-dot"></span>{{ $textoStatus }}</span></td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ VIEW: CONFIGURAÇÕES ============ -->
    <section class="view" id="view-settings">
      <div class="settings-card">
        <div class="settings-card-title">Perfil do Admin Master</div>
        <div class="settings-card-desc">Estes dados identificam-te como super administrador da plataforma.</div>
        <form action="{{ route('admins.update', Auth::id()) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="field-block">
            <label class="field-label">NOME</label>
            <input class="field-input" id="s-nome" name="name" type="text" value="{{ Auth::user()->name }}">
          </div>
          <div class="field-block">
            <label class="field-label">EMAIL</label>
            <input class="field-input" id="s-email" name="email" type="email" value="{{ Auth::user()->email }}">
          </div>
          <div class="field-block" style="margin-bottom:0;">
            <label class="field-label">NOVA PALAVRA-PASSE</label>
            <input class="field-input" id="s-senha" name="password" type="password" placeholder="Deixa em branco para manter a atual">
          </div>
          <div class="modal-actions" style="margin-top:18px; padding-top:16px;">
            <button class="btn btn-gold">Guardar alterações</button>
          </div>
        </form>
      </div>

      <div class="settings-card">
        <div class="settings-card-title">Notificações</div>
        <div class="settings-card-desc">Escolhe quando queres ser avisado sobre a atividade da plataforma.</div>

        <div class="toggle-row">
          <div>
            <div class="toggle-row-label">Novo comprovativo submetido</div>
            <div class="toggle-row-sub">Um salão enviou um pagamento para validação</div>
          </div>
          <label class="switch"><input type="checkbox" checked><span class="switch-track"></span></label>
        </div>
        <div class="toggle-row">
          <div>
            <div class="toggle-row-label">Salão prestes a expirar</div>
            <div class="toggle-row-sub">Avisar quando faltarem 5 dias para o fim da mensalidade</div>
          </div>
          <label class="switch"><input type="checkbox" checked><span class="switch-track"></span></label>
        </div>
        <div class="toggle-row">
          <div>
            <div class="toggle-row-label">Resumo semanal por email</div>
            <div class="toggle-row-sub">Receita, novos salões e cancelamentos da semana</div>
          </div>
          <label class="switch"><input type="checkbox"><span class="switch-track"></span></label>
        </div>
      </div>
    </section>

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
          
          {{-- Hidden Admin ID --}}
          <input type="hidden" name="admin_id" value="{{ Auth::id() }}">
          <input type="hidden" name="plano" value="10000">

          <div class="field-block full">
            <label class="field-label">SENHA DE ACESSO</label>
            <div class="field-with-action">
              <input class="field-input" name="password" type="text" placeholder="Senha provisória" style="padding-right:112px;" required>
              <div class="field-inline-actions">
                <button type="button" class="field-inline-action" onclick="gerarSenha(this)">Gerar</button>
                <button type="button" class="field-inline-action" onclick="copiarValor(this)">Copiar</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- PASSO 2: EQUIPA (OPCIONAL) -->
      <div class="wizard-step" id="wizardStep2">
        <div class="section-label">Funcionários (opcional)</div>
        <div id="funcionarioEmpty" class="funcionario-empty">
          Ainda sem funcionários adicionados. Podes avançar sem equipa e adicionar depois.
        </div>
        <div class="funcionario-list" id="funcionarioList"></div>
        <button type="button" class="add-funcionario-btn" onclick="addFuncionarioRow('wizard')">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
          Adicionar funcionário
        </button>
        <div class="skip-note">Cada funcionário recebe um login e senha próprios. Podes gerir a equipa a qualquer momento nos detalhes da empresa.</div>
      </div>

    </form>

    <div class="modal-actions" id="wizardActions1">
      <button class="btn btn-ghost" onclick="closeModal('salonModal')">Cancelar</button>
      <button class="btn btn-gold" onclick="goToStep(2)">Continuar</button>
    </div>

    <div class="modal-actions" id="wizardActions2" style="display:none;">
      <button class="btn btn-ghost" onclick="goToStep(1)">Voltar</button>
      <button class="btn btn-gold" onclick="document.getElementById('salonForm').submit()">Ativar empresa</button>
    </div>
  </div>
</div>

<!-- ============ MODAL: DETALHES DO SALÃO ============ -->
<div class="modal-overlay" id="detailModal">
  <div class="modal" style="max-width:560px;">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="detailTitle">Detalhes do salão</div>
      </div>
      <div class="modal-close" onclick="closeModal('detailModal')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </div>
    </div>
    <div class="modal-desc" id="detailStatusLine">—</div>

    <form id="editSalonForm" method="POST">
        @csrf
        @method('PUT')

      <div class="section-label">Empresa</div>
      <div class="form-grid">
        <div class="field-block full">
          <label class="field-label">NOME DA EMPRESA / LOJA</label>
          <input class="field-input" id="d-nome" name="name" type="text">
        </div>
        <div class="field-block">
          <label class="field-label">MUNICÍPIO</label>
          <input class="field-input" id="d-municipio" name="municipio" type="text">
        </div>
        <div class="field-block">
          <label class="field-label">PLANO</label>
          <select class="field-select" id="d-plano" name="plano">
            <option value="10000">Mensal · 10.000 Kz</option>
          </select>
        </div>
      </div>

      <div class="section-label" style="margin-top:6px;">Gestor da loja</div>
      <div class="form-grid">
        <div class="field-block full">
          <label class="field-label">NOME DO GESTOR</label>
          <input class="field-input" id="d-dono" name="gestor" type="text">
        </div>
        <div class="field-block">
          <label class="field-label">EMAIL DE LOGIN</label>
          <input class="field-input" id="d-email" name="email" type="email">
        </div>
        <div class="field-block">
          <label class="field-label">TELEFONE</label>
          <input class="field-input" id="d-telefone" name="number" type="text">
        </div>
        <div class="field-block full">
          <label class="field-label">SENHA DE ACESSO</label>
          <div class="field-with-action">
            <input class="field-input" id="d-senha" name="password" type="text" placeholder="Senha de acesso" style="padding-right:112px;">
            <div class="field-inline-actions">
              <button type="button" class="field-inline-action" onclick="gerarSenha(this)">Gerar</button>
              <button type="button" class="field-inline-action" onclick="copiarValor(this)">Copiar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-actions" style="justify-content:space-between;">
        <button type="button" class="btn btn-danger-ghost" id="detailSuspendBtn" onclick="toggleActiveStatus()">Suspender</button>
        <div style="display:flex; gap:10px;">
          <button type="button" class="btn btn-ghost" onclick="closeModal('detailModal')">Fechar</button>
          <button type="submit" class="btn btn-gold">Guardar</button>
        </div>
      </div>
    </form>
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

<div class="toast" id="toast">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="toastMsg">Feito.</span>
</div>

<script>
  let currentFilter = "todos";

  function setFilter(f){
    currentFilter = f;
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.toggle('active', c.dataset.filter === f));
    filterTable();
  }

  function filterTable(){
    const term = document.getElementById('searchInput').value.trim().toLowerCase();
    const rows = document.querySelectorAll('.salon-row');
    
    rows.forEach(row => {
        const name = row.dataset.name || '';
        const owner = row.dataset.owner || '';
        const status = row.dataset.status || '';
        
        const matchesTerm = !term || name.includes(term) || owner.includes(term);
        const matchesFilter = currentFilter === 'todos' || status === currentFilter;

        row.style.display = (matchesTerm && matchesFilter) ? '' : 'none';
    });
  }

  // ============================================================
  // NAVEGAÇÃO ENTRE VIEWS
  // ============================================================
  const VIEW_META = {
    overview: { title: 'Salões registados', sub: 'Gere todos os estabelecimentos, mensalidades e acessos da plataforma.', showTopbarActions: true },
    billing:  { title: 'Faturação SaaS', sub: 'Acompanha a receita recorrente e o estado de pagamento de cada salão.', showTopbarActions: false },
    settings: { title: 'Configurações', sub: 'Dados da tua conta de Admin Master e preferências de notificação.', showTopbarActions: false },
  };

  function switchView(view){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    document.getElementById('view-' + view).classList.add('active');

    document.querySelectorAll('.nav-item, .mobile-nav-item').forEach(n => {
      n.classList.toggle('active', n.dataset.view === view);
    });

    const meta = VIEW_META[view];
    document.getElementById('pageTitle').textContent = meta.title;
    document.getElementById('pageSub').textContent = meta.sub;
    document.getElementById('topbarActions').style.display = meta.showTopbarActions ? 'flex' : 'none';

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // ============================================================
  // MODAIS & HELPERS
  // ============================================================
  
  function openCreateModal(){
    document.getElementById('salonForm').reset();
    document.getElementById('salonModal').classList.add('open');
    goToStep(1);
  }

  function closeModal(id){
    document.getElementById(id).classList.remove('open');
  }

  function goToStep(step){
    // Validação simples do passo 1
    if(step === 2){
      const nome = document.querySelector('#wizardStep1 input[name="name"]').value;
      const email = document.querySelector('#wizardStep1 input[name="email"]').value;
      if(!nome || !email) {
          showToast('Preencha os dados da empresa e gestor.');
          return;
      }
    }

    document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
    document.getElementById('wizardStep' + step).classList.add('active');
    
    document.getElementById('dot-1').classList.toggle('done', step > 1);
    document.getElementById('dot-1').classList.toggle('current', step === 1);
    document.getElementById('dot-2').classList.toggle('current', step === 2);
    
    document.getElementById('stepLabel').textContent = step === 1
      ? 'Passo 1 de 2 · Empresa e gestor'
      : 'Passo 2 de 2 · Equipa (opcional)';
      
    document.getElementById('wizardActions1').style.display = step === 1 ? 'flex' : 'none';
    document.getElementById('wizardActions2').style.display = step === 2 ? 'flex' : 'none';
  }

  function gerarSenha(btn) {
      const input = btn.parentElement.previousElementSibling;
      const letras = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
      const l1 = letras[Math.floor(Math.random() * letras.length)];
      const l2 = letras[Math.floor(Math.random() * letras.length)];
      const num = Math.floor(1000 + Math.random() * 9000);
      input.value = 'Ng' + l1 + l2 + num;
  }

  function copiarValor(btn) {
      const input = btn.parentElement.previousElementSibling;
      input.select();
      document.execCommand('copy');
      showToast('Copiado!');
  }

  // Funcionários (UI Only for now)
  const funcionarioStore = { wizard: [] };
  let funcionarioSeq = 0;

  function addFuncionarioRow(scope){
    funcionarioSeq++;
    const list = document.getElementById('funcionarioList');
    const empty = document.getElementById('funcionarioEmpty');
    empty.style.display = 'none';
    
    const row = document.createElement('div');
    row.className = 'funcionario-row';
    row.innerHTML = `
        <div class="funcionario-row-top">
          <input class="field-input" placeholder="Nome do funcionário">
          <input class="field-input" placeholder="Email de login">
        </div>
        <div class="funcionario-row-bottom">
          <div class="field-with-action">
            <input class="field-input" placeholder="Senha" style="padding-right:104px;">
            <div class="field-inline-actions">
              <button type="button" class="field-inline-action" onclick="gerarSenha(this)">Gerar</button>
            </div>
          </div>
          <button type="button" class="funcionario-remove" title="Remover" onclick="this.closest('.funcionario-row').remove()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
          </button>
        </div>
    `;
    list.appendChild(row);
  }

  // Preencher Modal de Edição com dados JSON
  function openDetailModal(barbearia) {
      const form = document.getElementById('editSalonForm');
      form.action = `/barbearias/${barbearia.id}`; // Rota dinâmica
      
      document.getElementById('detailTitle').textContent = barbearia.name;
      
      // Preencher campos
      document.getElementById('d-nome').value = barbearia.name;
      document.getElementById('d-municipio').value = barbearia.municipio;
      document.getElementById('d-plano').value = barbearia.plano;
      document.getElementById('d-dono').value = barbearia.gestor;
      document.getElementById('d-email').value = barbearia.email;
      document.getElementById('d-telefone').value = barbearia.number;

      // Configurar botão de suspender/reativar
      const suspendBtn = document.getElementById('detailSuspendBtn');
      // Input hidden para controlar isactive no update
      if (!form.querySelector('input[name="isactive"]')) {
          const hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'isactive';
          hiddenInput.id = 'd-isactive';
          form.appendChild(hiddenInput);
      }
      
      const isActive = barbearia.isactive;
      document.getElementById('d-isactive').value = isActive ? 1 : 0;

      if(isActive) {
          suspendBtn.textContent = 'Suspender';
          suspendBtn.className = 'btn btn-danger-ghost';
          suspendBtn.onclick = () => toggleActiveStatus(false);
      } else {
          suspendBtn.textContent = 'Reativar';
          suspendBtn.className = 'btn btn-gold';
          suspendBtn.onclick = () => toggleActiveStatus(true);
      }

      document.getElementById('detailModal').classList.add('open');
  }

  function toggleActiveStatus(forceValue = null) {
      const input = document.getElementById('d-isactive');
      if (forceValue !== null) {
          input.value = forceValue ? 1 : 0;
      } else {
          input.value = input.value == 1 ? 0 : 1;
      }
      
      // Atualizar visualmente o botão e submeter
      const btn = document.getElementById('detailSuspendBtn');
      if(input.value == 1) {
          btn.textContent = 'Suspender';
          btn.className = 'btn btn-danger-ghost';
      } else {
          btn.textContent = 'Reativar';
          btn.className = 'btn btn-gold';
      }
      
      // Submeter formulário automaticamente para salvar a mudança de status
      document.getElementById('editSalonForm').submit();
  }

  // ============================================================
  // CONFIRMAÇÃO DE EXCLUSÃO DE BARBEARIA
  // ============================================================
  function confirmDeleteBarbearia(btn){
    const form = btn.closest('form');
    document.getElementById('confirmTitle').textContent = 'Remover barbearia';
    document.getElementById('confirmDesc').textContent = 'Tem certeza que deseja remover esta barbearia? Esta ação não pode ser desfeita.';
    document.getElementById('confirmActionBtn').onclick = () => {
      closeModal('confirmModal');
      form.submit();
    };
    document.getElementById('confirmModal').classList.add('open');
  }

  // Toast notification simples
  function showToast(msg){
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3200);
  }

  // Fechar modal ao clicar fora
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
      if(e.target === overlay) overlay.classList.remove('open');
    });
  });

  // Exibir toast se houver mensagem de sucesso do Laravel
  @if(session('success'))
      showToast("{{ session('success') }}");
  @endif

</script>

</body>
</html>
