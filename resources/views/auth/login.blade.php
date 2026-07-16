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
</style>
</head>
<body>

<div class="screen">

  <!-- ============ DESKTOP: BRAND PANEL ============ -->
  <div class="brand-panel">
    <div class="brand-header">
      <div class="brand-mark"><img src="data:image/png;base64,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" alt="Logo Nguevela"></div>
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

      <div class="mobile-logo"><img src="data:image/png;base64,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" alt="Logo Nguevela"></div>

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
          @if (Route::has('password.request'))
            <a class="link-gold" href="{{ route('password.request') }}">Esqueceu-se da palavra-passe?</a>
          @endif
        </div>

        <button class="btn-primary" type="submit">Entrar na plataforma</button>
      </form>

      <div class="form-footnote">Acesso restrito a Admin Master, admin do salão e equipa autorizada.</div>
    </div>
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
</script>

</body>
</html>
