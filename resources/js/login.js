/**
 * Nguevela Beauty — Login Page Scripts
 */

window.togglePwd = function togglePwd() {
  const x = document.getElementById("password");
  if (x) {
    x.type = x.type === "password" ? "text" : "password";
  }
};

window.togglePwdMobile = function togglePwdMobile() {
  const x = document.getElementById("password_mobile");
  if (x) {
    x.type = x.type === "password" ? "text" : "password";
  }
};

window.showRecover = function showRecover() {
  const overlay = document.getElementById('recoverOverlay');
  if (overlay) {
    overlay.classList.add('show');
  }
};

window.hideRecover = function hideRecover() {
  const overlay = document.getElementById('recoverOverlay');
  if (overlay) {
    overlay.classList.remove('show');
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const overlay = document.getElementById('recoverOverlay');
  if (overlay) {
    overlay.addEventListener('click', e => {
      if (e.target.id === 'recoverOverlay') {
        window.hideRecover();
      }
    });
  }

  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', (e) => {
      const passInput = form.querySelector('input[type="password"]');
      if (passInput && passInput.value.length < 6) {
        e.preventDefault();
        const fieldWrap = passInput.closest('.field') || passInput.closest('.mobile-field') || passInput;
        if (fieldWrap) fieldWrap.classList.add('field-error');
        passInput.focus();
        passInput.select();

        let errorBanner = form.querySelector('.form-inline-error');
        if (!errorBanner) {
          errorBanner = document.createElement('div');
          errorBanner.className = 'form-inline-error';
          errorBanner.style.cssText = 'color:#EF4444; background:#FEF2F2; border:1px solid #FCA5A5; padding:10px 14px; border-radius:10px; font-size:13px; margin-bottom:16px; font-weight:500; text-align:left;';
          const title = form.querySelector('h1, h2, .card-title, .mobile-title');
          if (title && title.nextSibling) {
            form.insertBefore(errorBanner, title.nextSibling);
          } else {
            form.insertBefore(errorBanner, form.firstChild);
          }
        }
        errorBanner.textContent = 'A palavra-passe deve ter no mínimo 6 caracteres. Digite novamente.';
        return false;
      }
    });
  });
});
