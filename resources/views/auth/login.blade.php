<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nguevela Beauty · Iniciar sessão</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg-deep:#0B0B0D;
    --bg-panel:#141416;
    --field-bg:#1F1F22;
    --field-border:#333336;
    --line:#3A3A3D;
    --node-dim:#6b5a1f;
    --gold:#D4AF37;
    --gold-dark:#241C05;
    --text-primary:#F5F3EE;
    --text-secondary:#8F8D87;
    --text-faint:#5C5A54;
    --radius:8px;
    --error-bg: #3d1a1a;
    --error-text: #ff9999;
  }

  *{ box-sizing:border-box; margin:0; padding:0; }

  button:focus,
  button:focus-visible,
  a:focus,
  a:focus-visible,
  input:focus,
  input:focus-visible,
  input[type="checkbox"]:focus,
  input[type="checkbox"]:focus-visible{
    outline:none;
  }

  html,body{
    height:100%;
    font-family:'Inter', sans-serif;
    background:var(--bg-deep);
    color:var(--text-primary);
  }

  .screen{
    min-height:100vh;
    display:flex;
  }

  /* ---------- LEFT / BRAND PANEL ---------- */
  .brand-panel{
    flex:1.05;
    background:linear-gradient(155deg, #0E0E10 0%, #0B0B0D 55%, #0A0A0B 100%);
    position:relative;
    padding:48px 44px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    overflow:hidden;
  }

  .brand-panel::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:repeating-linear-gradient(
      124deg,
      rgba(212,175,55,0.035) 0px,
      rgba(212,175,55,0.035) 1px,
      transparent 1px,
      transparent 64px
    );
    pointer-events:none;
  }

  .brand-header{
    display:flex;
    align-items:center;
    gap:12px;
    position:relative;
    z-index:2;
  }

  .brand-mark{
    width:42px;
    height:42px;
    border-radius:8px;
    overflow:hidden;
    flex-shrink:0;
  }

  .brand-mark img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }

  .brand-name{
    font-family:'Oswald', sans-serif;
    font-weight:600;
    font-size:19px;
    color:var(--text-primary);
    letter-spacing:0.3px;
  }

  .brand-tag{
    font-size:12px;
    letter-spacing:1.5px;
    color:var(--gold);
    margin-top:2px;
  }

  .constellation{
    position:absolute;
    top:170px;
    left:0;
    width:100%;
    height:230px;
    opacity:0.9;
  }

  .brand-copy{
    position:relative;
    z-index:2;
    max-width:460px;
  }

  .brand-headline{
    font-family:'Oswald', sans-serif;
    font-weight:600;
    font-size:38px;
    line-height:1.25;
    color:var(--text-primary);
    margin-bottom:16px;
  }

  .brand-sub{
    font-size:16px;
    color:var(--text-secondary);
    line-height:1.7;
  }

  .brand-footer{
    position:relative;
    z-index:2;
    font-size:13px;
    color:var(--text-faint);
  }

  /* ---------- RIGHT / FORM PANEL ---------- */
  .form-panel{
    flex:1;
    background:var(--bg-panel);
    padding:64px 56px;
    display:flex;
    flex-direction:column;
    justify-content:center;
  }

  .form-wrap{
    max-width:380px;
    width:100%;
    margin:0 auto;
  }

  .form-title{
    font-family:'Oswald', sans-serif;
    font-weight:600;
    font-size:30px;
    color:var(--text-primary);
    margin-bottom:8px;
  }

  .form-desc{
    font-size:15px;
    color:var(--text-secondary);
    margin-bottom:32px;
    line-height:1.6;
  }

  .field-label{
    font-size:12.5px;
    letter-spacing:0.8px;
    color:var(--text-secondary);
    margin-bottom:6px;
  }

  .field{
    display:flex;
    align-items:center;
    gap:10px;
    background:var(--field-bg);
    border:0.5px solid var(--field-border);
    border-radius:var(--radius);
    padding:12px 14px;
    margin-bottom:20px;
    transition:border-color .15s ease;
  }

  .field:focus-within{
    border-color:var(--gold);
  }

  .field input{
    flex:1;
    background:none;
    border:none;
    outline:none;
    font-size:16px;
    color:var(--text-primary);
    font-family:'Inter', sans-serif;
  }

  .field input::placeholder{
    color:var(--text-faint);
  }

  .field svg{
    flex-shrink:0;
    color:var(--text-secondary);
  }

  .field .toggle-eye{
    cursor:pointer;
  }

  .form-row-between{
    display:flex;
    justify-content:flex-end;
    margin-bottom:28px;
    margin-top:-8px;
  }

  .link-gold{
    font-size:13.5px;
    color:var(--gold);
    text-decoration:none;
    cursor:pointer;
  }

  .btn-primary{
    width:100%;
    background:var(--gold);
    color:var(--gold-dark);
    text-align:center;
    font-weight:600;
    font-size:16px;
    padding:14px;
    border-radius:var(--radius);
    border:none;
    margin-bottom:20px;
    cursor:pointer;
    font-family:'Inter', sans-serif;
    transition:filter .15s ease;
  }

  .btn-primary:hover{
    filter:brightness(1.06);
  }

  .form-footnote{
    text-align:center;
    font-size:13px;
    color:var(--text-faint);
  }

  /* Error Messages */
  .alert-error {
    background: var(--error-bg);
    color: var(--error-text);
    padding: 12px;
    border-radius: var(--radius);
    margin-bottom: 20px;
    font-size: 14px;
    border: 1px solid #5c2828;
  }

  /* ---------- MOBILE: CENTERED CARD LOGIN (hidden on desktop) ---------- */
  .mobile-login{
    display:none;
  }

  .mobile-card{
    width:100%;
    max-width:380px;
    background:var(--bg-panel);
    border-radius:24px;
    padding:40px 30px 34px;
    box-shadow:0 20px 60px rgba(0,0,0,0.45);
  }

  .mobile-logo{
    width:88px;
    height:88px;
    margin:0 auto 22px;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 0 40px rgba(212,175,55,0.18);
  }

  .mobile-logo img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }

  .mobile-name{
    text-align:center;
    font-family:'Oswald', sans-serif;
    font-weight:600;
    font-size:26px;
    margin-bottom:6px;
  }

  .mobile-name .c-white{ color:var(--text-primary); }
  .mobile-name .c-gold{ color:var(--gold); }

  .mobile-tagline{
    text-align:center;
    font-size:13px;
    color:var(--text-secondary);
    margin-bottom:30px;
  }

  .mobile-field-label{
    font-size:13px;
    font-weight:500;
    color:var(--text-primary);
    margin-bottom:8px;
  }

  .mobile-field{
    display:flex;
    align-items:center;
    gap:10px;
    background:#E9EEFB;
    border-radius:10px;
    padding:13px 14px;
    margin-bottom:18px;
  }

  .mobile-field input{
    flex:1;
    background:none;
    border:none;
    outline:none;
    font-size:15px;
    color:#20242E;
    font-family:'Inter', sans-serif;
  }

  .mobile-field input::placeholder{ color:#7C8195; }

  .mobile-field svg{
    flex-shrink:0;
    color:#5B6072;
  }

  .mobile-field .toggle-eye{ cursor:pointer; }

  .mobile-remember{
    display:flex;
    align-items:center;
    gap:10px;
    margin:8px 0 26px;
  }

  .mobile-remember input[type="checkbox"]{
    width:19px;
    height:19px;
    border-radius:5px;
    accent-color:var(--gold);
    cursor:pointer;
  }

  .mobile-remember label{
    font-size:13px;
    color:var(--text-secondary);
  }

  .mobile-btn{
    width:100%;
    background:var(--gold);
    color:var(--gold-dark);
    text-align:center;
    font-weight:700;
    font-size:15px;
    padding:15px;
    border-radius:10px;
    border:none;
    cursor:pointer;
    font-family:'Inter', sans-serif;
  }

  /* ==================================================== */
  /* MOBILE LAYOUT                                          */
  /* ==================================================== */
  @media (max-width: 768px){

    .brand-panel,
    .form-panel{
      display:none;
    }

    .mobile-login{
      display:flex;
      align-items:center;
      justify-content:center;
      min-height:100vh;
      background:var(--bg-deep);
      padding:24px;
    }

    .screen{
      flex-direction:column;
      min-height:auto;
    }
  }

  /* ============================================================
     RECUPERAR PALAVRA-PASSE (overlay)
     ============================================================ */
  .recover-overlay{
    position:fixed; inset:0; z-index:300;
    background:rgba(5,5,6,0.82);
    backdrop-filter:blur(6px);
    display:none;
    align-items:center;
    justify-content:center;
    padding:24px;
  }
  .recover-overlay.show{ display:flex; }

  .recover-card{
    width:100%;
    max-width:420px;
    background:var(--bg-panel);
    border:1px solid var(--field-border);
    border-radius:20px;
    padding:34px 30px 28px;
    box-shadow:0 24px 70px rgba(0,0,0,0.5);
    animation:recoverIn .2s ease;
  }
  @keyframes recoverIn{ from{ opacity:0; transform:translateY(10px) scale(0.98); } to{ opacity:1; transform:none; } }

  .recover-back{
    display:inline-flex; align-items:center; gap:6px;
    background:none; border:none; color:var(--text-secondary);
    font-size:12.5px; font-weight:500; cursor:pointer; padding:0;
    margin-bottom:20px; font-family:'Inter', sans-serif;
    transition:color .15s ease;
  }
  .recover-back:hover{ color:var(--gold); }

  .recover-icon{
    width:48px; height:48px; border-radius:13px;
    background:rgba(212,175,55,0.12); color:var(--gold);
    display:flex; align-items:center; justify-content:center;
    margin-bottom:16px;
  }

  .recover-title{
    font-family:'Oswald', sans-serif;
    font-weight:600;
    font-size:21px;
    margin-bottom:8px;
  }

  .recover-desc{
    font-size:13.5px;
    color:var(--text-secondary);
    line-height:1.6;
    margin-bottom:24px;
  }

  .recover-options{
    display:flex;
    flex-direction:column;
    gap:12px;
    margin-bottom:22px;
  }

  .recover-option{
    display:flex;
    align-items:center;
    gap:13px;
    background:var(--field-bg);
    border:1px solid var(--field-border);
    border-radius:12px;
    padding:14px 15px;
    text-decoration:none;
    color:var(--text-primary);
    transition:border-color .15s ease, background .15s ease, transform .1s ease;
  }
  .recover-option:hover{ border-color:var(--gold); background:rgba(212,175,55,0.06); }
  .recover-option:active{ transform:scale(0.98); }

  .recover-option-icon{
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
  }
  .recover-option-icon.whatsapp{ background:rgba(76,175,109,0.14); color:#4CAF6D; }
  .recover-option-icon.email{ background:rgba(76,147,175,0.14); color:#4C93AF; }

  .recover-option-text{ flex:1; min-width:0; }
  .recover-option-label{ font-size:13.5px; font-weight:600; }
  .recover-option-value{ font-size:12px; color:var(--text-faint); margin-top:1px; word-break:break-all; }

  .recover-option-arrow{ color:var(--text-faint); flex-shrink:0; }

  .recover-footnote{
    text-align:center;
    font-size:11.5px;
    color:var(--text-faint);
    line-height:1.6;
  }
</style>
</head>
<body>

<div class="screen">

  <!-- ============ DESKTOP: BRAND PANEL ============ -->
  <div class="brand-panel">
    <div class="brand-header">
      <div class="brand-mark"><img src="==" alt="Logo Nguevela"></div>
      <div>
        <div class="brand-name">Nguevela Beauty</div>
        <div class="brand-tag">GESTÃO INTELIGENTE PARA SALÕES</div>
      </div>
    </div>

    <svg class="constellation" viewBox="0 0 460 230" aria-hidden="true">
      <line x1="70" y1="70" x2="180" y2="40" stroke="var(--line)" stroke-width="1"/>
      <line x1="180" y1="40" x2="300" y2="90" stroke="var(--line)" stroke-width="1"/>
      <line x1="300" y1="90" x2="400" y2="60" stroke="var(--line)" stroke-width="1"/>
      <line x1="180" y1="40" x2="220" y2="150" stroke="var(--line)" stroke-width="1"/>
      <line x1="220" y1="150" x2="340" y2="180" stroke="var(--line)" stroke-width="1"/>
      <line x1="70" y1="70" x2="140" y2="170" stroke="var(--line)" stroke-width="1"/>
      <line x1="140" y1="170" x2="220" y2="150" stroke="var(--line)" stroke-width="1"/>
      <circle cx="70" cy="70" r="4" fill="var(--gold)"/>
      <circle cx="180" cy="40" r="5" fill="var(--gold)"/>
      <circle cx="300" cy="90" r="4" fill="var(--node-dim)"/>
      <circle cx="400" cy="60" r="3.5" fill="var(--node-dim)"/>
      <circle cx="220" cy="150" r="5" fill="var(--gold)"/>
      <circle cx="340" cy="180" r="4" fill="var(--node-dim)"/>
      <circle cx="140" cy="170" r="3.5" fill="var(--node-dim)"/>
      <g transform="translate(178,120) rotate(28)">
        <rect x="0" y="0" width="60" height="6" rx="2" fill="#2A2A2D" stroke="var(--gold)" stroke-width="0.5"/>
        <rect x="60" y="-3" width="14" height="12" rx="2" fill="var(--gold)"/>
      </g>
    </svg>

    <div class="brand-copy">
      <div class="brand-headline">O motor de gestão<br>para o seu salão<br>crescer, em Angola.</div>
      <div class="brand-sub">Fecho de caixa em tempo real e catálogo de serviços, tudo numa só plataforma.</div>
    </div>

    <div class="brand-footer">Nguevela Beauty © 2026 · Luanda, Angola. Todos os direitos reservados.</div>
  </div>

  <!-- ============ MOBILE: CENTERED CARD LOGIN ============ -->
  <div class="mobile-login">
    <div class="mobile-card">

      <div class="mobile-logo"><img src="==" alt="Logo Nguevela"></div>

      <div class="mobile-name"><span class="c-white">Nguevela</span><span class="c-gold">Beauty</span></div>
      <div class="mobile-tagline">Gestão simplificada</div>

      <form method="POST" action="{{ route('login.store') }}">
        @csrf
        
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mobile-field-label">Utilizador</div>
        <div class="mobile-field">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
          <input id="email_mobile" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@nguevelabeauty.ao">
        </div>

        <div class="mobile-field-label">Senha</div>
        <div class="mobile-field">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
          <input id="password_mobile" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
          <svg class="toggle-eye" onclick="togglePwdMobile()" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>

        <div class="mobile-remember">
          <input type="checkbox" name="remember" id="remember-mobile">
          <label for="remember-mobile">Lembrar palavra-passe (30 dias)</label>
        </div>

        <div style="text-align:center; margin-bottom:18px;">
          <a class="link-gold" style="font-size:12.5px;" onclick="showRecover()">Esqueceu-se da palavra-passe?</a>
        </div>

        <button class="mobile-btn" type="submit">Entrar no sistema</button>
      </form>

    </div>
  </div>

  <!-- ============ FORM PANEL (desktop) ============ -->
  <div class="form-panel">
    <div class="form-wrap">
      <div class="form-title">Iniciar sessão</div>
      <div class="form-desc">Aceda à conta do seu salão para gerir atendimentos e faturação.</div>

      <form method="POST" action="{{ route('login.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="field-label">EMAIL</div>
        <div class="field">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@nguevelabeauty.ao">
        </div>

        <div class="field-label">PALAVRA-PASSE</div>
        <div class="field">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
          <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
          <svg class="toggle-eye" onclick="togglePwd()" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>

        <div class="form-row-between">
          <a class="link-gold" onclick="showRecover()">Esqueceu-se da palavra-passe?</a>
        </div>

        <button class="btn-primary" type="submit">Entrar na plataforma</button>
      </form>

      <div class="form-footnote">Acesso restrito a Admin Master, admin do salão e equipa autorizada.</div>
    </div>
  </div>

</div>

<!-- ============ RECUPERAR PALAVRA-PASSE (overlay) ============ -->
<div class="recover-overlay" id="recoverOverlay">
  <div class="recover-card">
    <button class="recover-back" onclick="hideRecover()">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      Voltar ao login
    </button>

    <div class="recover-icon">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>

    <div class="recover-title">Recuperar palavra-passe</div>
    <div class="recover-desc">Por segurança, a redefinição da tua palavra-passe é feita diretamente com a equipa Nguevela. Contacta-nos por um dos canais abaixo e trata-se do assunto rapidamente.</div>

    <div class="recover-options">
      <a class="recover-option" href="https://wa.me/244950781962?text=Ol%C3%A1%2C%20preciso%20de%20ajuda%20para%20recuperar%20a%20minha%20palavra-passe%20do%20Nguevela%20Beauty." target="_blank" rel="noopener">
        <div class="recover-option-icon whatsapp">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.2h.01c5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2Zm5.79 14.03c-.24.68-1.4 1.32-1.93 1.4-.49.08-1.11.11-1.79-.11-.41-.13-.95-.31-1.63-.6-2.87-1.24-4.74-4.14-4.89-4.34-.14-.19-1.17-1.56-1.17-2.98 0-1.42.74-2.11 1-2.4.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.49.24.58.81 2 .88 2.15.07.15.12.32.02.51-.1.19-.15.31-.29.48-.15.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.29.76 1.25 1.63 2.02 1.12.99 2.06 1.3 2.36 1.45.29.14.46.12.63-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.63-.14.26.1 1.64.77 1.92.91.29.14.48.22.55.34.07.13.07.72-.17 1.4Z"/></svg>
        </div>
        <div class="recover-option-text">
          <div class="recover-option-label">Falar no WhatsApp</div>
          <div class="recover-option-value">+244 950 781 962</div>
        </div>
        <svg class="recover-option-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </a>

      <a class="recover-option" href="https://mail.google.com/mail/?view=cm&fs=1&to=nguevelasystem@gmail.com&su=Recupera%C3%A7%C3%A3o%20de%20palavra-passe%20-%20Nguevela%20Beauty&body=Ol%C3%A1%2C%20preciso%20de%20ajuda%20para%20recuperar%20a%20minha%20palavra-passe%20do%20Nguevela%20Beauty." target="_blank" rel="noopener">
        <div class="recover-option-icon email">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
        </div>
        <div class="recover-option-text">
          <div class="recover-option-label">Enviar email</div>
          <div class="recover-option-value">nguevelasystem@gmail.com</div>
        </div>
        <svg class="recover-option-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </a>
    </div>

    <div class="recover-footnote">A nossa equipa responde o mais rápido possível para restabelecer o teu acesso em segurança.</div>
  </div>
</div>

<script>
    function togglePwd() {
        const x = document.getElementById("password");
        if (x.type === "password") { x.type = "text"; } else { x.type = "password"; }
    }
    function togglePwdMobile() {
        const x = document.getElementById("password_mobile");
        if (x.type === "password") { x.type = "text"; } else { x.type = "password"; }
    }

    function showRecover(){
        document.getElementById('recoverOverlay').classList.add('show');
    }

    function hideRecover(){
        document.getElementById('recoverOverlay').classList.remove('show');
    }

    document.getElementById('recoverOverlay').addEventListener('click', e => {
        if(e.target.id === 'recoverOverlay') hideRecover();
    });
</script>

</body>
</html>