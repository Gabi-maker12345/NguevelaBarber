<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nguevela · Admin Master</title>
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
    <div class="nav-item" data-view="overview" onclick="switchView('overview')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-7h6v7"/></svg>
      Salões
    </div>
    <div class="nav-item" data-view="billing" onclick="switchView('billing')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
      Faturação SaaS
    </div>
    <div class="nav-item" data-view="settings" onclick="switchView('settings')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Configurações
    </div>

    <div class="nav-group-label">Suporte</div>
    <div class="nav-item" data-view="requests" onclick="switchView('requests')">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Pedidos e comprovativos
      <span id="requestsBadge" style="display:none; margin-left:auto; background:var(--danger); color:#fff; font-size:10.5px; font-weight:700; padding:1px 6px; border-radius:10px;"></span>
    </div>

    <div class="sidebar-footer">
      <div class="admin-chip">
        <div class="admin-avatar">AM</div>
        <div>
          <div class="admin-name">Admin Master</div>
          <div class="admin-email">master@nguevela.ao</div>
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
          <input id="searchInput" type="text" placeholder="Procurar salão ou dono..." oninput="renderTable()">
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
        <div class="kpi-value" id="kpiTotal">—</div>
        <div class="kpi-delta">em todo o país</div>
      </div>

      <div class="kpi-card">
        <div class="kpi-top">
          <div class="kpi-label">Ativos</div>
          <div class="kpi-icon" style="background:var(--ok-soft); color:var(--ok);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          </div>
        </div>
        <div class="kpi-value" id="kpiAtivos">—</div>
        <div class="kpi-delta up" id="kpiAtivosPct">—</div>
      </div>

      <div class="kpi-card">
        <div class="kpi-top">
          <div class="kpi-label">A expirar em 5 dias</div>
          <div class="kpi-icon" style="background:var(--warn-soft); color:var(--warn);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          </div>
        </div>
        <div class="kpi-value" id="kpiExpirar">—</div>
        <div class="kpi-delta warn">requer atenção</div>
      </div>

      <div class="kpi-card">
        <div class="kpi-top">
          <div class="kpi-label">Receita mensal (Kz)</div>
          <div class="kpi-icon" style="background:var(--gold-soft); color:var(--gold);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
        </div>
        <div class="kpi-value" id="kpiReceita">—</div>
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
          <tbody id="salonsBody"></tbody>
        </table>
      </div>
      <div id="emptyState" class="empty-state" style="display:none;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <div class="empty-state-title">Nenhum salão encontrado</div>
        <div>Tenta outro termo de pesquisa ou filtro.</div>
      </div>
    </div>

    </section>

    <!-- ============ VIEW: FATURAÇÃO SAAS ============ -->
    <section class="view" id="view-billing">
      <div class="summary-row">
        <div class="summary-card">
          <div class="summary-label">Receita mensal recorrente</div>
          <div class="summary-value" id="billingMRR">—</div>
          <div class="summary-note">soma dos planos de salões não suspensos</div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Em atraso / suspensos</div>
          <div class="summary-value" id="billingOverdue">—</div>
          <div class="summary-note" id="billingOverdueValue">—</div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Ticket médio por salão</div>
          <div class="summary-value" id="billingAvg">—</div>
          <div class="summary-note">baseado no plano contratado</div>
        </div>
      </div>

      <div class="panel" style="margin-bottom:20px;">
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Plano</th><th>Preço</th><th>Salões ativos</th><th>Receita do plano</th></tr>
            </thead>
            <tbody id="billingPlanBody"></tbody>
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
            <tbody id="billingSalonBody"></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ VIEW: PEDIDOS E COMPROVATIVOS ============ -->
    <section class="view" id="view-requests">
      <div class="summary-row">
        <div class="summary-card">
          <div class="summary-label">Pendentes</div>
          <div class="summary-value" id="reqPendentes" style="color:var(--warn);">—</div>
          <div class="summary-note">à espera de validação</div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Aprovados este mês</div>
          <div class="summary-value" id="reqAprovados" style="color:var(--ok);">—</div>
          <div class="summary-note">comprovativos validados</div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Rejeitados</div>
          <div class="summary-value" id="reqRejeitados" style="color:var(--danger);">—</div>
          <div class="summary-note">comprovativo inválido ou insuficiente</div>
        </div>
      </div>

      <div class="panel" id="requestsPanel"></div>
    </section>

    <!-- ============ VIEW: CONFIGURAÇÕES ============ -->
    <section class="view" id="view-settings">
      <div class="settings-card">
        <div class="settings-card-title">Perfil do Admin Master</div>
        <div class="settings-card-desc">Estes dados identificam-te como super administrador da plataforma.</div>
        <form onsubmit="return false;">
          <div class="field-block">
            <label class="field-label">NOME</label>
            <input class="field-input" id="s-nome" type="text" value="Admin Master">
          </div>
          <div class="field-block">
            <label class="field-label">EMAIL</label>
            <input class="field-input" id="s-email" type="email" value="master@nguevela.ao">
          </div>
          <div class="field-block" style="margin-bottom:0;">
            <label class="field-label">NOVA PALAVRA-PASSE</label>
            <input class="field-input" id="s-senha" type="password" placeholder="Deixa em branco para manter a atual">
          </div>
        </form>
        <div class="modal-actions" style="margin-top:18px; padding-top:16px;">
          <button class="btn btn-gold" onclick="saveSettings()">Guardar alterações</button>
        </div>
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
    <div class="mobile-nav-item" data-view="requests" onclick="switchView('requests')" style="position:relative;">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Pedidos
      <span id="requestsBadgeMobile" style="display:none; position:absolute; top:-2px; right:6px; width:8px; height:8px; border-radius:50%; background:var(--danger);"></span>
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

    <form id="salonForm" onsubmit="return false;">

      <!-- PASSO 1: EMPRESA + GESTOR -->
      <div class="wizard-step active" id="wizardStep1">

        <div class="section-label">Empresa</div>
        <div class="form-grid">
          <div class="field-block full">
            <label class="field-label">NOME DA EMPRESA / LOJA</label>
            <input class="field-input" id="f-nome" type="text" placeholder="Ex: Barbearia Central Luanda" required>
          </div>
          <div class="field-block full">
            <label class="field-label">MUNICÍPIO</label>
            <input class="field-input" id="f-municipio" type="text" placeholder="Ex: Talatona" required>
          </div>
        </div>

        <div class="section-label" style="margin-top:6px;">Gestor da loja</div>
        <div class="form-grid">
          <div class="field-block full">
            <label class="field-label">NOME DO GESTOR</label>
            <input class="field-input" id="f-dono" type="text" placeholder="Ex: João Neto" required>
          </div>
          <div class="field-block">
            <label class="field-label">EMAIL DE LOGIN</label>
            <input class="field-input" id="f-email" type="email" placeholder="gestor@empresa.ao" required>
          </div>
          <div class="field-block">
            <label class="field-label">TELEFONE</label>
            <input class="field-input" id="f-telefone" type="text" placeholder="9XX XXX XXX" required>
          </div>
          <div class="field-block full">
            <label class="field-label">SENHA DE ACESSO</label>
            <div class="field-with-action">
              <input class="field-input" id="f-senha" type="text" placeholder="Senha provisória" style="padding-right:112px;" required>
              <div class="field-inline-actions">
                <button type="button" class="field-inline-action" onclick="gerarSenha('f-senha')">Gerar</button>
                <button type="button" class="field-inline-action" onclick="copiarValor('f-senha')">Copiar</button>
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
      <button class="btn btn-gold" onclick="saveSalon()">Ativar empresa</button>
    </div>
  </div>
</div>

<!-- ============ MODAL: CONFIRMAR RENOVAÇÃO ============ -->
<div class="modal-overlay" id="renewModal">
  <div class="modal" style="max-width:380px;">
    <div class="confirm-icon" style="background:var(--ok-soft); color:var(--ok);">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8M3 22v-6h6M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
    </div>
    <div class="modal-title" id="renewTitle">Renovar assinatura</div>
    <div class="modal-desc" id="renewDesc">Isto estende o acesso do salão por mais 30 dias a partir de hoje.</div>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal('renewModal')">Cancelar</button>
      <button class="btn btn-gold" onclick="confirmRenew()">Confirmar +30 dias</button>
    </div>
  </div>
</div>

<!-- ============ MODAL: CONFIRMAR SUSPENSÃO ============ -->
<div class="modal-overlay" id="suspendModal">
  <div class="modal" style="max-width:380px;">
    <div class="confirm-icon" style="background:var(--danger-soft); color:var(--danger);">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
    </div>
    <div class="modal-title" id="suspendTitle">Suspender salão</div>
    <div class="modal-desc" id="suspendDesc">A equipa deste salão perde o acesso imediatamente. Podes reativar a qualquer momento.</div>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal('suspendModal')">Cancelar</button>
      <button class="btn btn-danger-ghost" onclick="confirmSuspend()">Sim, suspender</button>
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

    <form onsubmit="return false;">

      <div class="section-label">Empresa</div>
      <div class="form-grid">
        <div class="field-block full">
          <label class="field-label">NOME DA EMPRESA / LOJA</label>
          <input class="field-input" id="d-nome" type="text">
        </div>
        <div class="field-block">
          <label class="field-label">MUNICÍPIO</label>
          <input class="field-input" id="d-municipio" type="text">
        </div>
        <div class="field-block">
          <label class="field-label">PLANO</label>
          <select class="field-select" id="d-plano">
            <option value="Mensal">Mensal · 10.000 Kz</option>
          </select>
        </div>
      </div>

      <div class="section-label" style="margin-top:6px;">Gestor da loja</div>
      <div class="form-grid">
        <div class="field-block full">
          <label class="field-label">NOME DO GESTOR</label>
          <input class="field-input" id="d-dono" type="text">
        </div>
        <div class="field-block">
          <label class="field-label">EMAIL DE LOGIN</label>
          <input class="field-input" id="d-email" type="email">
        </div>
        <div class="field-block">
          <label class="field-label">TELEFONE</label>
          <input class="field-input" id="d-telefone" type="text">
        </div>
        <div class="field-block full">
          <label class="field-label">SENHA DE ACESSO</label>
          <div class="field-with-action">
            <input class="field-input" id="d-senha" type="text" placeholder="Senha de acesso" style="padding-right:112px;">
            <div class="field-inline-actions">
              <button type="button" class="field-inline-action" onclick="gerarSenha('d-senha')">Gerar</button>
              <button type="button" class="field-inline-action" onclick="copiarValor('d-senha')">Copiar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="section-label" style="margin-top:6px;">Equipa</div>
      <div id="d-funcionarioEmpty" class="funcionario-empty">
        Ainda sem funcionários com login individual registado.
      </div>
      <div class="funcionario-list" id="d-funcionarioList"></div>
      <button type="button" class="add-funcionario-btn" onclick="addFuncionarioRow('detail')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Adicionar funcionário
      </button>
    </form>

    <div class="modal-actions" style="justify-content:space-between;">
      <button class="btn btn-danger-ghost" id="detailSuspendBtn" onclick="detailSuspendToggle()">Suspender</button>
      <div style="display:flex; gap:10px;">
        <button class="btn btn-ghost" onclick="closeModal('detailModal')">Fechar</button>
        <button class="btn btn-gold" onclick="saveDetail()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="toastMsg">Feito.</span>
</div>

<script>
  // ---------- DADOS DE EXEMPLO (substituir por chamadas à API / Supabase) ----------
  const PLAN_PRICES = { "Mensal": 10000 };

  let salons = [
    { id: 1, nome: "Barbearia Real Luanda", dono: "Kiluanje Sami", municipio: "Ingombota", plano: "Mensal", expira: addDays(-3), equipa: 4, status: "suspenso" },
    { id: 2, nome: "Elegance Hair Studio", dono: "Marta Cabinda", municipio: "Talatona", plano: "Mensal", expira: addDays(2), equipa: 9, status: "ativo" },
    { id: 3, nome: "Corte Fino Barbershop", dono: "Edson Paulo", municipio: "Viana", plano: "Mensal", expira: addDays(21), equipa: 2, status: "ativo" },
    { id: 4, nome: "Golden Fade Barbers", dono: "Nelson Kiala", municipio: "Talatona", plano: "Mensal", expira: addDays(4), equipa: 5, status: "ativo" },
    { id: 5, nome: "Beauty House Benfica", dono: "Rosa Manuel", municipio: "Benfica", plano: "Mensal", expira: addDays(30), equipa: 6, status: "ativo" },
  ];

  let currentFilter = "todos";
  let pendingActionId = null;

  function addDays(n){
    const d = new Date();
    d.setDate(d.getDate() + n);
    return d;
  }

  function formatDate(d){
    return d.toLocaleDateString('pt-AO', { day:'2-digit', month:'short', year:'numeric' });
  }

  function daysUntil(d){
    const diff = Math.ceil((d - new Date()) / (1000*60*60*24));
    return diff;
  }

  function computeStatus(salon){
    if(salon.status === 'suspenso') return 'suspenso';
    const dias = daysUntil(salon.expira);
    if(dias < 0) return 'suspenso';
    if(dias <= 5) return 'expirar';
    return 'ativo';
  }

  function initials(name){
    return name.split(' ').filter(Boolean).slice(0,2).map(w => w[0]).join('').toUpperCase();
  }

  function setFilter(f){
    currentFilter = f;
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.toggle('active', c.dataset.filter === f));
    renderTable();
  }

  function renderKpis(){
    const total = salons.length;
    const ativos = salons.filter(s => computeStatus(s) === 'ativo').length;
    const expirar = salons.filter(s => computeStatus(s) === 'expirar').length;
    const receita = salons.filter(s => computeStatus(s) !== 'suspenso').reduce((sum, s) => sum + PLAN_PRICES[s.plano], 0);

    document.getElementById('kpiTotal').textContent = total;
    document.getElementById('kpiAtivos').textContent = ativos;
    document.getElementById('kpiAtivosPct').textContent = total ? Math.round((ativos/total)*100) + '% da base' : '—';
    document.getElementById('kpiExpirar').textContent = expirar;
    document.getElementById('kpiReceita').textContent = receita.toLocaleString('pt-AO') + ' Kz';
  }

  function renderTable(){
    const body = document.getElementById('salonsBody');
    const empty = document.getElementById('emptyState');
    const term = document.getElementById('searchInput').value.trim().toLowerCase();

    let list = salons.filter(s => {
      const matchesTerm = !term || s.nome.toLowerCase().includes(term) || s.dono.toLowerCase().includes(term);
      const st = computeStatus(s);
      const matchesFilter = currentFilter === 'todos' || st === currentFilter;
      return matchesTerm && matchesFilter;
    });

    body.innerHTML = '';

    if(list.length === 0){
      empty.style.display = 'block';
      renderKpis();
      return;
    }
    empty.style.display = 'none';

    list.forEach(s => {
      const st = computeStatus(s);
      const dias = daysUntil(s.expira);
      const tr = document.createElement('tr');

      let badgeClass = 'ativo', badgeText = 'Ativo';
      if(st === 'expirar'){ badgeClass = 'expirar'; badgeText = `Expira em ${dias}d`; }
      if(st === 'suspenso'){ badgeClass = 'suspenso'; badgeText = dias < 0 ? 'Expirado' : 'Suspenso'; }

      tr.innerHTML = `
        <td class="cell-salon">
          <div class="salon-cell">
            <div class="salon-logo">${initials(s.nome)}</div>
            <div>
              <div class="salon-name">${s.nome}</div>
              <div class="salon-owner">${s.dono} · ${s.municipio}</div>
            </div>
          </div>
        </td>
        <td data-label="Plano"><span class="plan-tag">${s.plano} · ${PLAN_PRICES[s.plano].toLocaleString('pt-AO')} Kz</span></td>
        <td data-label="Expira em">${formatDate(s.expira)}</td>
        <td data-label="Status"><span class="status-badge ${badgeClass}"><span class="status-dot"></span>${badgeText}</span></td>
        <td data-label="Equipa">${s.equipa} ${s.equipa === 1 ? 'pessoa' : 'pessoas'}</td>
        <td class="cell-actions">
          <div class="row-actions">
            <button class="icon-btn" title="Renovar 30 dias" onclick="askRenew(${s.id})">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8M3 22v-6h6M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
            </button>
            ${st === 'suspenso'
              ? `<button class="icon-btn" title="Reativar" onclick="reactivate(${s.id})">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                 </button>`
              : `<button class="icon-btn danger" title="Suspender" onclick="askSuspend(${s.id})">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                 </button>`
            }
            <button class="icon-btn" title="Ver detalhes" onclick="openDetail(${s.id})">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </td>
      `;
      body.appendChild(tr);
    });

    renderKpis();
  }

  // ---------- MODAIS ----------
  const funcionarioStore = { wizard: [], detail: [] };
  let funcionarioSeq = 0;

  function openCreateModal(){
    document.getElementById('salonModalTitle').textContent = 'Nova empresa';
    document.getElementById('salonForm').reset();
    funcionarioStore.wizard = [];
    renderFuncionarios('wizard');
    goToStep(1);
    document.getElementById('salonModal').classList.add('open');
  }

  function goToStep(step){
    document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));

    if(step === 2){
      // valida passo 1 antes de avançar
      const nome = document.getElementById('f-nome').value.trim();
      const municipio = document.getElementById('f-municipio').value.trim();
      const dono = document.getElementById('f-dono').value.trim();
      const email = document.getElementById('f-email').value.trim();
      const telefone = document.getElementById('f-telefone').value.trim();
      const senha = document.getElementById('f-senha').value.trim();
      if(!nome || !municipio || !dono || !email || !telefone || !senha){
        showToast('Preenche os dados da empresa e do gestor antes de continuar.');
        return;
      }
      if(!isValidEmail(email)){
        showToast('Introduz um email de login válido para o gestor.');
        return;
      }
    }

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

  function gerarSenhaValor(){
    const letras = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // sem I/O, evita confusão ao ler em voz alta
    const l1 = letras[Math.floor(Math.random() * letras.length)];
    const l2 = letras[Math.floor(Math.random() * letras.length)];
    const num = Math.floor(1000 + Math.random() * 9000);
    return 'Ng' + l1 + l2 + num;
  }

  function gerarSenha(inputId){
    document.getElementById(inputId).value = gerarSenhaValor();
  }

  function copyText(text){
    if(!text){ return; }
    if(navigator.clipboard && navigator.clipboard.writeText){
      navigator.clipboard.writeText(text).then(
        () => showToast('Senha copiada para a área de transferência.'),
        () => fallbackCopy(text)
      );
    } else {
      fallbackCopy(text);
    }
  }

  function fallbackCopy(text){
    try{
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
      showToast('Senha copiada para a área de transferência.');
    } catch(e){
      showToast('Não foi possível copiar automaticamente. Copia manualmente.');
    }
  }

  function copiarValor(inputId){
    copyText(document.getElementById(inputId).value.trim());
  }

  function copiarSenhaFuncionario(scope, rowId){
    const f = funcionarioStore[scope].find(x => x.rowId === rowId);
    if(f) copyText(f.senha);
  }

  function isValidEmail(v){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  function escAttr(str){
    return String(str == null ? '' : str)
      .replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  function addFuncionarioRow(scope){
    funcionarioSeq++;
    funcionarioStore[scope].push({ rowId: funcionarioSeq, nome: '', email: '', senha: gerarSenhaValor() });
    renderFuncionarios(scope);
  }

  function removeFuncionarioRow(scope, rowId){
    funcionarioStore[scope] = funcionarioStore[scope].filter(f => f.rowId !== rowId);
    renderFuncionarios(scope);
  }

  function updateFuncionarioField(scope, rowId, field, value){
    const f = funcionarioStore[scope].find(x => x.rowId === rowId);
    if(f) f[field] = value;
  }

  function gerarSenhaFuncionario(scope, rowId){
    const f = funcionarioStore[scope].find(x => x.rowId === rowId);
    if(!f) return;
    f.senha = gerarSenhaValor();
    renderFuncionarios(scope);
  }

  function renderFuncionarios(scope){
    const listId = scope === 'wizard' ? 'funcionarioList' : 'd-funcionarioList';
    const emptyId = scope === 'wizard' ? 'funcionarioEmpty' : 'd-funcionarioEmpty';
    const list = document.getElementById(listId);
    const empty = document.getElementById(emptyId);
    const arr = funcionarioStore[scope];
    list.innerHTML = '';

    if(arr.length === 0){
      empty.style.display = 'block';
      return;
    }
    empty.style.display = 'none';

    arr.forEach(f => {
      const row = document.createElement('div');
      row.className = 'funcionario-row';
      row.innerHTML = `
        <div class="funcionario-row-top">
          <input class="field-input" placeholder="Nome do funcionário" value="${escAttr(f.nome)}" oninput="updateFuncionarioField('${scope}',${f.rowId},'nome',this.value)">
          <input class="field-input" placeholder="Email de login" value="${escAttr(f.email)}" oninput="updateFuncionarioField('${scope}',${f.rowId},'email',this.value)">
        </div>
        <div class="funcionario-row-bottom">
          <div class="field-with-action">
            <input class="field-input" placeholder="Senha" value="${escAttr(f.senha)}" style="padding-right:104px;" oninput="updateFuncionarioField('${scope}',${f.rowId},'senha',this.value)">
            <div class="field-inline-actions">
              <button type="button" class="field-inline-action" onclick="gerarSenhaFuncionario('${scope}',${f.rowId})">Gerar</button>
              <button type="button" class="field-inline-action" onclick="copiarSenhaFuncionario('${scope}',${f.rowId})">Copiar</button>
            </div>
          </div>
          <button type="button" class="funcionario-remove" title="Remover" onclick="removeFuncionarioRow('${scope}',${f.rowId})">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
          </button>
        </div>
      `;
      list.appendChild(row);
    });
  }

  function closeModal(id){
    document.getElementById(id).classList.remove('open');
    pendingActionId = null;
  }

  function saveSalon(){
    const nome = document.getElementById('f-nome').value.trim();
    const dono = document.getElementById('f-dono').value.trim();
    const email = document.getElementById('f-email').value.trim();
    const telefone = document.getElementById('f-telefone').value.trim();
    const municipio = document.getElementById('f-municipio').value.trim();
    const senha = document.getElementById('f-senha').value.trim();
    const plano = 'Mensal';

    if(!nome || !dono || !email || !telefone || !municipio || !senha){
      showToast('Preenche todos os campos antes de continuar.');
      goToStep(1);
      return;
    }
    if(!isValidEmail(email)){
      showToast('Introduz um email de login válido para o gestor.');
      goToStep(1);
      return;
    }

    // ignora linhas de funcionário deixadas em branco
    const funcionarios = funcionarioStore.wizard
      .filter(f => f.nome.trim() && f.email.trim())
      .map(f => ({ nome: f.nome.trim(), email: f.email.trim(), senha: (f.senha || '').trim() || gerarSenhaValor() }));

    const newSalon = {
      id: Date.now(),
      nome, dono, municipio, plano,
      email, telefone,
      gestor: { nome: dono, email, telefone, senha },
      funcionarios,
      expira: addDays(30),
      equipa: 1 + funcionarios.length,
      status: 'ativo'
    };
    salons.unshift(newSalon);
    closeModal('salonModal');
    setFilter('todos');
    const equipaMsg = funcionarios.length
      ? ` com ${funcionarios.length} funcionário(s) já registados`
      : '';
    showToast(`${nome} foi ativado com 30 dias de acesso${equipaMsg}.`);
  }

  function askRenew(id){
    pendingActionId = id;
    const s = salons.find(x => x.id === id);
    document.getElementById('renewDesc').textContent = `${s.nome} vai ficar ativo por mais 30 dias, até ${formatDate(addDays(30))}.`;
    document.getElementById('renewModal').classList.add('open');
  }

  function confirmRenew(){
    const s = salons.find(x => x.id === pendingActionId);
    if(s){
      s.expira = addDays(30);
      s.status = 'ativo';
    }
    closeModal('renewModal');
    renderTable();
    showToast(`Mensalidade de ${s.nome} renovada por mais 30 dias.`);
  }

  function askSuspend(id){
    pendingActionId = id;
    const s = salons.find(x => x.id === id);
    document.getElementById('suspendDesc').textContent = `A equipa de ${s.nome} vai perder o acesso à plataforma imediatamente.`;
    document.getElementById('suspendModal').classList.add('open');
  }

  function confirmSuspend(){
    const s = salons.find(x => x.id === pendingActionId);
    if(s) s.status = 'suspenso';
    closeModal('suspendModal');
    renderTable();
    showToast(`${s.nome} foi suspenso.`);
  }

  function reactivate(id){
    const s = salons.find(x => x.id === id);
    if(s){
      s.status = 'ativo';
      if(daysUntil(s.expira) < 0) s.expira = addDays(30);
    }
    renderTable();
    showToast(`${s.nome} foi reativado.`);
  }

  // ============================================================
  // NAVEGAÇÃO ENTRE VIEWS
  // ============================================================
  const VIEW_META = {
    overview: { title: 'Salões registados', sub: 'Gere todos os estabelecimentos, mensalidades e acessos da plataforma.', showTopbarActions: true },
    billing:  { title: 'Faturação SaaS', sub: 'Acompanha a receita recorrente e o estado de pagamento de cada salão.', showTopbarActions: false },
    requests: { title: 'Pedidos e comprovativos', sub: 'Valida os comprovativos de pagamento enviados pelos donos dos salões.', showTopbarActions: false },
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

    if(view === 'billing') renderBilling();
    if(view === 'requests') renderRequests();

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // ============================================================
  // PEDIDOS E COMPROVATIVOS
  // ============================================================
  let requests = [
    { id: 101, salaoNome: "Vibe Cuts Barbershop", dono: "Paulo Sema", plano: "Mensal", valor: 10000, data: addDays(-1), status: "pendente" },
    { id: 102, salaoNome: "Barbearia Real Luanda", dono: "Kiluanje Sami", plano: "Mensal", valor: 10000, data: addDays(0), status: "pendente", salaoId: 1 },
    { id: 103, salaoNome: "Studio Nzinga", dono: "Amélia Vunge", plano: "Mensal", valor: 10000, data: addDays(-2), status: "pendente" },
    { id: 104, salaoNome: "Elegance Hair Studio", dono: "Marta Cabinda", plano: "Mensal", valor: 10000, data: addDays(-6), status: "aprovado", salaoId: 2 },
    { id: 105, salaoNome: "Fade Kings", dono: "Rui Fortunato", plano: "Mensal", valor: 10000, data: addDays(-8), status: "rejeitado" },
  ];

  function updateRequestsBadge(){
    const pendentes = requests.filter(r => r.status === 'pendente').length;
    const badge = document.getElementById('requestsBadge');
    const badgeMobile = document.getElementById('requestsBadgeMobile');
    if(pendentes > 0){
      badge.textContent = pendentes;
      badge.style.display = 'inline-block';
      badgeMobile.style.display = 'block';
    } else {
      badge.style.display = 'none';
      badgeMobile.style.display = 'none';
    }
  }

  function renderRequests(){
    const panel = document.getElementById('requestsPanel');
    panel.innerHTML = '';

    document.getElementById('reqPendentes').textContent = requests.filter(r => r.status === 'pendente').length;
    document.getElementById('reqAprovados').textContent = requests.filter(r => r.status === 'aprovado').length;
    document.getElementById('reqRejeitados').textContent = requests.filter(r => r.status === 'rejeitado').length;

    if(requests.length === 0){
      panel.innerHTML = `<div class="empty-state">
        <div class="empty-state-title">Sem pedidos por agora</div>
        <div>Quando um salão enviar um comprovativo, aparece aqui.</div>
      </div>`;
      return;
    }

    // pendentes primeiro
    const ordered = [...requests].sort((a,b) => (a.status === 'pendente' ? -1 : 1) - (b.status === 'pendente' ? -1 : 1));

    ordered.forEach(r => {
      const card = document.createElement('div');
      card.className = 'request-card';

      let statusHtml = '';
      let actionsHtml = '';
      if(r.status === 'pendente'){
        statusHtml = `<span class="request-status" style="background:var(--warn-soft); color:var(--warn);"><span class="status-dot"></span>Pendente</span>`;
        actionsHtml = `
          <button class="btn btn-sm btn-ghost" onclick="rejectRequest(${r.id})">Rejeitar</button>
          <button class="btn btn-sm btn-gold" onclick="approveRequest(${r.id})">Aprovar</button>`;
      } else if(r.status === 'aprovado'){
        statusHtml = `<span class="request-status" style="background:var(--ok-soft); color:var(--ok);"><span class="status-dot"></span>Aprovado</span>`;
      } else {
        statusHtml = `<span class="request-status" style="background:var(--danger-soft); color:var(--danger);"><span class="status-dot"></span>Rejeitado</span>`;
      }

      card.innerHTML = `
        <div class="request-main">
          <div class="request-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          </div>
          <div style="min-width:0;">
            <div class="request-title">${r.salaoNome}</div>
            <div class="request-sub">${r.dono} · plano ${r.plano} · enviado a ${formatDate(r.data)}</div>
          </div>
        </div>
        <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
          <div class="request-value">${r.valor.toLocaleString('pt-AO')} Kz</div>
          ${statusHtml}
          <div class="request-actions">${actionsHtml}</div>
        </div>
      `;
      panel.appendChild(card);
    });

    updateRequestsBadge();
  }

  function approveRequest(id){
    const r = requests.find(x => x.id === id);
    if(!r) return;
    r.status = 'aprovado';

    // se já existe salão associado, renova; senão, ativa como novo salão
    let salon = r.salaoId ? salons.find(s => s.id === r.salaoId) : salons.find(s => s.nome === r.salaoNome);
    let isNew = false;
    if(salon){
      salon.expira = addDays(30);
      salon.status = 'ativo';
    } else {
      isNew = true;
      salon = {
        id: Date.now(),
        nome: r.salaoNome,
        dono: r.dono,
        municipio: '—',
        plano: r.plano,
        expira: addDays(30),
        equipa: 1,
        status: 'ativo'
      };
      salons.unshift(salon);
      r.salaoId = salon.id;
    }

    renderRequests();
    renderTable();
    const msg = isNew
      ? `Comprovativo de ${r.salaoNome} aprovado. Falta definir o login do gestor nos detalhes do salão.`
      : `Comprovativo de ${r.salaoNome} aprovado. Acesso liberado por 30 dias.`;
    showToast(msg);
  }

  function rejectRequest(id){
    const r = requests.find(x => x.id === id);
    if(!r) return;
    r.status = 'rejeitado';
    renderRequests();
    showToast(`Comprovativo de ${r.salaoNome} rejeitado.`);
  }

  // ============================================================
  // FATURAÇÃO SAAS
  // ============================================================
  function renderBilling(){
    const ativos = salons.filter(s => computeStatus(s) !== 'suspenso');
    const suspensos = salons.filter(s => computeStatus(s) === 'suspenso');

    const mrr = ativos.reduce((sum, s) => sum + PLAN_PRICES[s.plano], 0);
    const overdueValue = suspensos.reduce((sum, s) => sum + PLAN_PRICES[s.plano], 0);
    const avg = ativos.length ? Math.round(mrr / ativos.length) : 0;

    document.getElementById('billingMRR').textContent = mrr.toLocaleString('pt-AO') + ' Kz';
    document.getElementById('billingOverdue').textContent = suspensos.length;
    document.getElementById('billingOverdueValue').textContent = overdueValue.toLocaleString('pt-AO') + ' Kz em risco';
    document.getElementById('billingAvg').textContent = avg.toLocaleString('pt-AO') + ' Kz';

    // por plano
    const planBody = document.getElementById('billingPlanBody');
    planBody.innerHTML = '';
    Object.keys(PLAN_PRICES).forEach(plano => {
      const count = ativos.filter(s => s.plano === plano).length;
      const receita = count * PLAN_PRICES[plano];
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td data-label="Plano"><strong>${plano}</strong></td>
        <td data-label="Preço">${PLAN_PRICES[plano].toLocaleString('pt-AO')} Kz</td>
        <td data-label="Salões ativos">${count}</td>
        <td data-label="Receita do plano">${receita.toLocaleString('pt-AO')} Kz</td>
      `;
      planBody.appendChild(tr);
    });

    // por salão
    const salonBody = document.getElementById('billingSalonBody');
    salonBody.innerHTML = '';
    salons.forEach(s => {
      const st = computeStatus(s);
      let badgeClass = 'ativo', badgeText = 'Em dia';
      if(st === 'expirar') { badgeClass = 'expirar'; badgeText = `Vence em ${daysUntil(s.expira)}d`; }
      if(st === 'suspenso') { badgeClass = 'suspenso'; badgeText = 'Em atraso'; }

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td data-label="Salão">${s.nome}</td>
        <td data-label="Plano">${s.plano}</td>
        <td data-label="Valor">${PLAN_PRICES[s.plano].toLocaleString('pt-AO')} Kz</td>
        <td data-label="Próxima cobrança">${formatDate(s.expira)}</td>
        <td data-label="Estado"><span class="status-badge ${badgeClass}"><span class="status-dot"></span>${badgeText}</span></td>
      `;
      salonBody.appendChild(tr);
    });
  }

  // ============================================================
  // CONFIGURAÇÕES
  // ============================================================
  function saveSettings(){
    const nome = document.getElementById('s-nome').value.trim();
    const email = document.getElementById('s-email').value.trim();
    if(!nome || !email){
      showToast('Preenche nome e email antes de guardar.');
      return;
    }
    document.querySelector('.admin-name').textContent = nome;
    document.querySelector('.admin-email').textContent = email;
    document.getElementById('s-senha').value = '';
    showToast('Dados do Admin Master atualizados.');
  }

  // ============================================================
  // MODAL DE DETALHES / EDIÇÃO DO SALÃO
  // ============================================================
  let detailSalonId = null;

  function openDetail(id){
    const s = salons.find(x => x.id === id);
    if(!s) return;
    detailSalonId = id;

    document.getElementById('detailTitle').textContent = s.nome;
    const st = computeStatus(s);
    const dias = daysUntil(s.expira);
    let statusLine = st === 'ativo' ? `Ativo · expira a ${formatDate(s.expira)}`
                    : st === 'expirar' ? `A expirar em ${dias} dia(s) · ${formatDate(s.expira)}`
                    : `Suspenso desde ${formatDate(s.expira)}`;
    document.getElementById('detailStatusLine').textContent = statusLine;

    document.getElementById('d-nome').value = s.nome;
    document.getElementById('d-dono').value = s.gestor ? s.gestor.nome : s.dono;
    document.getElementById('d-municipio').value = s.municipio;
    document.getElementById('d-plano').value = s.plano;
    document.getElementById('d-email').value = s.gestor ? s.gestor.email : (s.email || '');
    document.getElementById('d-telefone').value = s.gestor ? s.gestor.telefone : (s.telefone || '');
    document.getElementById('d-senha').value = s.gestor ? s.gestor.senha : '';

    // carrega a equipa existente (salões antigos sem estrutura ficam com lista vazia, prontos a preencher)
    funcionarioStore.detail = (s.funcionarios || []).map(f => {
      funcionarioSeq++;
      return { rowId: funcionarioSeq, nome: f.nome, email: f.email, senha: f.senha };
    });
    renderFuncionarios('detail');

    const suspendBtn = document.getElementById('detailSuspendBtn');
    if(s.status === 'suspenso'){
      suspendBtn.textContent = 'Reativar salão';
      suspendBtn.className = 'btn btn-gold';
    } else {
      suspendBtn.textContent = 'Suspender';
      suspendBtn.className = 'btn btn-danger-ghost';
    }

    document.getElementById('detailModal').classList.add('open');
  }

  function saveDetail(){
    const s = salons.find(x => x.id === detailSalonId);
    if(!s) return;

    const nome = document.getElementById('d-nome').value.trim();
    const dono = document.getElementById('d-dono').value.trim();
    const municipio = document.getElementById('d-municipio').value.trim();
    const plano = document.getElementById('d-plano').value;
    const email = document.getElementById('d-email').value.trim();
    const telefone = document.getElementById('d-telefone').value.trim();
    const senha = document.getElementById('d-senha').value.trim();

    if(!nome || !dono || !municipio || !email || !telefone || !senha){
      showToast('Preenche os dados da empresa e do gestor antes de guardar.');
      return;
    }
    if(!isValidEmail(email)){
      showToast('Introduz um email de login válido para o gestor.');
      return;
    }

    const funcionarios = funcionarioStore.detail
      .filter(f => f.nome.trim() && f.email.trim())
      .map(f => ({ nome: f.nome.trim(), email: f.email.trim(), senha: (f.senha || '').trim() || gerarSenhaValor() }));

    s.nome = nome;
    s.dono = dono;
    s.municipio = municipio;
    s.plano = plano;
    s.gestor = { nome: dono, email, telefone, senha };
    s.funcionarios = funcionarios;
    s.equipa = 1 + funcionarios.length;

    closeModal('detailModal');
    renderTable();
    showToast(`Dados de ${nome} atualizados.`);
  }

  function detailSuspendToggle(){
    const s = salons.find(x => x.id === detailSalonId);
    if(!s) return;

    if(s.status === 'suspenso'){
      s.status = 'ativo';
      if(daysUntil(s.expira) < 0) s.expira = addDays(30);
      showToast(`${s.nome} foi reativado.`);
    } else {
      s.status = 'suspenso';
      showToast(`${s.nome} foi suspenso.`);
    }
    closeModal('detailModal');
    renderTable();
  }

  let toastTimer;
  function showToast(msg){
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 3200);
  }

  // fecha modal clicando fora
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
      if(e.target === overlay) overlay.classList.remove('open');
    });
  });

  renderTable();
  updateRequestsBadge();
</script>

</body>
</html>