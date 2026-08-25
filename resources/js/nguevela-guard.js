/**
 * ===================================================================
 * NGUEVELA BEAUTY — Frontend Validation & Subscription Guard Module
 * ===================================================================
 * 
 * Módulo completo e modular de validação de formulários, controle de
 * subscrição multi-tenant, restrições por perfil (RBAC) e feedback de UI/UX.
 *
 * Pilares cobertos:
 *  1. Validação de Formulários e Inputs (Email, Senha, Telefone AO, Preço)
 *  2. Verificação de Subscrição (Bloqueio Total, Alerta de Expiração)
 *  3. Controle de Acesso por Nível de Utilizador (Roles Guard)
 *  4. Feedback Visual (Toasts, Loading States, Validação em Tempo Real)
 */

(function (global) {
  'use strict';

  // =========================================================================
  // PILAR 1: VALIDAÇÃO DE FORMULÁRIOS E INPUTS (Client-Side)
  // =========================================================================

  const Validators = {
    /**
     * Valida o nome da empresa / salão (Mínimo de 3 caracteres)
     */
    salonName(name) {
      const val = (name || '').trim();
      if (!val) return { valid: false, message: 'O nome da empresa/salão é obrigatório.' };
      if (val.length < 3) return { valid: false, message: 'O nome deve ter pelo menos 3 caracteres.' };
      return { valid: true, message: '' };
    },

    /**
     * Valida email com regex RFC 5322 simplificado
     */
    email(email) {
      const val = (email || '').trim();
      if (!val) return { valid: false, message: 'O e-mail de acesso é obrigatório.' };
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      if (!emailRegex.test(val)) return { valid: false, message: 'Introduza um endereço de e-mail válido.' };
      return { valid: true, message: '' };
    },

    /**
     * Valida palavra-passe (mínimo 6 caracteres) e calcula força
     */
    password(password, minLength = 6) {
      const val = password || '';
      if (!val) return { valid: false, message: 'A palavra-passe é obrigatória.', strength: 0 };
      if (val.length < minLength) {
        return { 
          valid: false, 
          message: `A palavra-passe deve ter no mínimo ${minLength} caracteres.`,
          strength: Math.min(val.length / minLength, 0.5) 
        };
      }

      let strength = 0.5;
      if (/[A-Z]/.test(val)) strength += 0.2;
      if (/[0-9]/.test(val)) strength += 0.2;
      if (/[^A-Za-z0-9]/.test(val)) strength += 0.1;

      return { valid: true, message: '', strength: Math.min(strength, 1) };
    },

    /**
     * Valida número de telemóvel angolano (Unitel/Movicel/Africell: 9XX XXX XXX)
     */
    angolaPhone(phone) {
      const clean = (phone || '').replace(/[^\d]/g, '');
      
      // Se tiver DDI 244 no início, remove
      let number = clean;
      if (number.startsWith('244') && number.length === 12) {
        number = number.slice(3);
      }

      if (!number) return { valid: false, message: 'O número de telefone é obrigatório.' };
      
      // Prefixos válidos em Angola: 91X, 92X, 93X, 94X, 95X, 97X, 98X, 99X
      const angolaPhoneRegex = /^9[1-57-9]\d{7}$/;
      if (!angolaPhoneRegex.test(number)) {
        return { valid: false, message: 'Introduza um número válido de Angola (ex: 923 456 789).' };
      }

      return { 
        valid: true, 
        message: '', 
        formatted: `${number.slice(0, 3)} ${number.slice(3, 6)} ${number.slice(6, 9)}` 
      };
    },

    /**
     * Valida preços e valores de mensalidade (Positivos, numéricos)
     */
    price(price) {
      const clean = typeof price === 'string' ? price.replace(/[^\d.,]/g, '').replace(',', '.') : price;
      const num = Number(clean);
      if (isNaN(num) || num < 0) {
        return { valid: false, message: 'O valor deve ser um número positivo.' };
      }
      return { valid: true, message: '', value: num };
    }
  };

  /**
   * Aplica máscara de telefone de Angola em tempo real no input
   */
  function maskAngolaPhone(inputElement) {
    if (!inputElement) return;
    inputElement.addEventListener('input', (e) => {
      let v = e.target.value.replace(/\D/g, '');
      if (v.startsWith('244')) v = v.substring(3);
      if (v.length > 9) v = v.substring(0, 9);
      
      let formatted = v;
      if (v.length > 6) {
        formatted = `${v.slice(0, 3)} ${v.slice(3, 6)} ${v.slice(6)}`;
      } else if (v.length > 3) {
        formatted = `${v.slice(0, 3)} ${v.slice(3)}`;
      }
      e.target.value = formatted;
    });
  }

  // =========================================================================
  // PILAR 2: VERIFICAÇÃO DE SESSÃO E STATUS DA SUBSCRIÇÃO (Guards)
  // =========================================================================

  const SubscriptionGuard = {
    /**
     * Avalia o status da subscrição do salão e aplica a ação visual correspondente
     * @param {Object} barbearia { id, name, isactive, subscricao_expira_em, diasRestantes }
     */
    enforce(barbearia) {
      if (!barbearia) return;

      const isAtivo = Boolean(barbearia.isactive);
      const diasRestantes = typeof barbearia.diasRestantes === 'number' 
        ? barbearia.diasRestantes 
        : this.calcDaysRemaining(barbearia.subscricao_expira_em);

      // CASO 1: Salão Suspenso ou Subscrição Expirada (<= 0 dias)
      if (!isAtivo || diasRestantes <= 0) {
        this.renderLockoutScreen(barbearia, !isAtivo ? 'suspenso' : 'expirado');
        return false;
      }

      // CASO 2: Prestes a expirar (<= 5 dias) -> Alerta de aviso
      if (diasRestantes <= 5) {
        this.renderWarningBanner(barbearia, diasRestantes);
        return true;
      }

      // CASO 3: Ativo e em dia (> 5 dias)
      return true;
    },

    calcDaysRemaining(expireDateStr) {
      if (!expireDateStr) return 999;
      const expire = new Date(expireDateStr);
      const now = new Date();
      const diffTime = expire.getTime() - now.getTime();
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },

    /**
     * Renderiza o Banner de aviso no topo da página
     */
    renderWarningBanner(barbearia, diasRestantes) {
      if (document.getElementById('nguevela-sub-banner')) return;

      const banner = document.createElement('div');
      banner.id = 'nguevela-sub-banner';
      banner.className = 'sub-warning-banner';
      banner.innerHTML = `
        <div class="sub-banner-content">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span><strong>Aviso de Subscrição:</strong> A assinatura do <strong>${barbearia.name || 'seu salão'}</strong> expira em <strong>${diasRestantes} dia(s)</strong>. Regularize o pagamento para evitar a suspensão.</span>
        </div>
        <a href="https://wa.me/244950781962?text=Ol%C3%A1%2C%20pretendo%20renovar%20a%20subscri%C3%A7%C3%A3o%20do%20sal%C3%A3o%20${encodeURIComponent(barbearia.name || '')}" target="_blank" class="btn-banner-action">Renovar Agora</a>
      `;
      document.body.prepend(banner);
    },

    /**
     * Bloqueia completamente o acesso e exibe a tela de bloqueio
     */
    renderLockoutScreen(barbearia, motivo = 'expirado') {
      const existing = document.getElementById('nguevela-lockout');
      if (existing) return;

      const titulo = motivo === 'suspenso' ? 'Salão Suspenso' : 'Subscrição Expirada';
      const desc = motivo === 'suspenso'
        ? 'O acesso a este estabelecimento foi temporariamente suspenso pelo administrador do sistema.'
        : 'A subscrição deste estabelecimento encontra-se expirada. Para continuar a gerir e registar atendimentos, é necessário regularizar o pagamento.';

      const lockout = document.createElement('div');
      lockout.id = 'nguevela-lockout';
      lockout.className = 'lockout-overlay';
      lockout.innerHTML = `
        <div class="lockout-card">
          <div class="lockout-icon">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <h2 class="lockout-title">${titulo}</h2>
          <div class="lockout-salon">${barbearia.name || 'Estabelecimento'}</div>
          <p class="lockout-desc">${desc}</p>
          <div class="lockout-actions">
            <a href="https://wa.me/244950781962?text=Ol%C3%A1%2C%20preciso%20de%20ajuda%20com%20o%20acesso%20do%20sal%C3%A3o%20${encodeURIComponent(barbearia.name || '')}" target="_blank" class="btn-lockout-primary">
              Falar com o Suporte (WhatsApp)
            </a>
            <form action="/logout" method="POST" style="width:100%;">
              <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || ''}">
              <button type="submit" class="btn-lockout-secondary">Terminar Sessão</button>
            </form>
          </div>
        </div>
      `;
      document.body.appendChild(lockout);
      document.body.style.overflow = 'hidden';
    }
  };

  // =========================================================================
  // PILAR 3: RESTRIÇÕES DE PERMISSÃO POR NÍVEL DE ACESSO (Roles Guard)
  // =========================================================================

  const RoleGuard = {
    /**
     * Verifica e restringe a visibilidade de blocos HTML com base no cargo
     * @param {'admin_master' | 'barbearia' | 'user'} currentRole 
     */
    enforce(currentRole) {
      document.querySelectorAll('[data-allowed-roles]').forEach(el => {
        const allowed = el.getAttribute('data-allowed-roles').split(',').map(r => r.trim());
        if (!allowed.includes(currentRole)) {
          el.style.display = 'none';
          el.remove(); // Remove do DOM para segurança visual
        }
      });
    }
  };

  // =========================================================================
  // PILAR 4: FEEDBACK VISUAL, TOASTS & LOADING STATES
  // =========================================================================

  const UI = {
    /**
     * Aplica feedback visual de erro ou sucesso no input
     */
    setInputState(inputElement, isValid, errorMsg = '') {
      if (!inputElement) return;

      const parent = inputElement.closest('.field-block') || inputElement.parentElement;
      let errorContainer = parent.querySelector('.field-error-text');

      if (!isValid) {
        inputElement.classList.add('field-error');
        if (!errorContainer) {
          errorContainer = document.createElement('div');
          errorContainer.className = 'field-error-text';
          parent.appendChild(errorContainer);
        }
        errorContainer.textContent = errorMsg;
        errorContainer.style.display = 'block';
      } else {
        inputElement.classList.remove('field-error');
        if (errorContainer) {
          errorContainer.style.display = 'none';
        }
      }
    },

    /**
     * Ativa listeners de validação imediata em inputs de formulários
     */
    bindLiveValidation(formElement, rulesConfig) {
      if (!formElement) return;

      Object.entries(rulesConfig).forEach(([fieldName, validatorFn]) => {
        const input = formElement.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
        if (!input) return;

        const validate = () => {
          const result = validatorFn(input.value);
          this.setInputState(input, result.valid, result.message);
          return result.valid;
        };

        input.addEventListener('blur', validate);
        input.addEventListener('input', () => {
          if (input.classList.contains('field-error')) {
            validate();
          }
        });
      });
    },

    /**
     * Sistema moderno de Toast com suporte a tipo (success, error, warn, info)
     */
    toast(message, type = 'info', duration = 3500) {
      let toastContainer = document.getElementById('nguevela-toast-stack');
      if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'nguevela-toast-stack';
        toastContainer.className = 'toast-stack';
        document.body.appendChild(toastContainer);
      }

      const toastEl = document.createElement('div');
      toastEl.className = `toast-pill ${type}`;
      
      const icons = {
        success: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
        error: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        warn: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12.01" y2="16"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
      };

      toastEl.innerHTML = `
        <span class="toast-pill-icon">${icons[type] || icons.info}</span>
        <span class="toast-pill-text">${message}</span>
      `;

      toastContainer.appendChild(toastEl);
      setTimeout(() => toastEl.classList.add('show'), 10);

      setTimeout(() => {
        toastEl.classList.remove('show');
        setTimeout(() => toastEl.remove(), 300);
      }, duration);
    },

    /**
     * Alterna estado de carregamento em botões com spinner para prevenir múltiplos cliques
     */
    setLoading(buttonElement, isLoading, loadingText = 'A processar...') {
      if (!buttonElement) return;

      if (isLoading) {
        buttonElement.setAttribute('disabled', 'true');
        buttonElement.setAttribute('data-original-html', buttonElement.innerHTML);
        buttonElement.innerHTML = `
          <span class="btn-spinner"></span>
          <span>${loadingText}</span>
        `;
      } else {
        buttonElement.removeAttribute('disabled');
        const original = buttonElement.getAttribute('data-original-html');
        if (original) buttonElement.innerHTML = original;
      }
    }
  };

  // Exposição Global no objeto window
  global.NguevelaGuard = {
    Validators,
    SubscriptionGuard,
    RoleGuard,
    UI,
    maskAngolaPhone
  };

})(typeof window !== 'undefined' ? window : this);
