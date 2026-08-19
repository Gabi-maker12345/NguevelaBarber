/**
 * Nguevela Beauty — Admin Master Dashboard Module
 */

let currentFilter = "todos";
let currentDetailBarbeariaId = null;
let funcionarioSeq = 0;

const VIEW_META = {
  overview: { title: 'Salões registados', sub: 'Gere todos os estabelecimentos, mensalidades e acessos da plataforma.', showTopbarActions: true },
  billing:  { title: 'Faturação SaaS', sub: 'Acompanha a receita recorrente e o estado de pagamento de cada salão.', showTopbarActions: false },
  settings: { title: 'Configurações', sub: 'Dados da tua conta de Admin Master e preferências de notificação.', showTopbarActions: false },
};

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
}

window.isValidEmail = function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
};

window.setFilter = function setFilter(f) {
  currentFilter = f;
  document.querySelectorAll('.filter-chip').forEach(c => c.classList.toggle('active', c.dataset.filter === f));
  window.filterTable();
};

window.filterTable = function filterTable() {
  const searchEl = document.getElementById('searchInput');
  const term = searchEl ? searchEl.value.trim().toLowerCase() : '';
  const rows = document.querySelectorAll('#saloesTableBody tr[data-search]');

  rows.forEach(row => {
    const searchData = row.dataset.search || '';
    const statusData = row.dataset.status || '';

    const matchesTerm = !term || searchData.includes(term);
    const matchesFilter = currentFilter === 'todos' || statusData === currentFilter;

    row.style.display = (matchesTerm && matchesFilter) ? '' : 'none';
  });
};

window.switchView = function switchView(view) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  const viewEl = document.getElementById('view-' + view);
  if (viewEl) viewEl.classList.add('active');

  document.querySelectorAll('.nav-item, .mobile-nav-item').forEach(n => {
    n.classList.toggle('active', n.dataset.view === view);
  });

  const meta = VIEW_META[view];
  if (meta) {
    const titleEl = document.getElementById('pageTitle');
    const subEl = document.getElementById('pageSub');
    const actionsEl = document.getElementById('topbarActions');

    if (titleEl) titleEl.textContent = meta.title;
    if (subEl) subEl.textContent = meta.sub;
    if (actionsEl) actionsEl.style.display = meta.showTopbarActions ? 'flex' : 'none';
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });
};

window.openCreateModal = function openCreateModal() {
  const form = document.getElementById('salonForm');
  if (form) form.reset();

  document.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
  const modal = document.getElementById('salonModal');
  if (modal) modal.classList.add('open');
  window.goToStep(1);
};

window.closeModal = function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.remove('open');
};

window.goToStep = function goToStep(step) {
  if (step === 2) {
    const nomeInput = document.querySelector('#wizardStep1 input[name="name"]');
    const emailInput = document.querySelector('#wizardStep1 input[name="email"]');
    const passInput = document.querySelector('#wizardStep1 input[name="password"]');

    const nome = nomeInput ? nomeInput.value.trim() : '';
    const email = emailInput ? emailInput.value.trim() : '';
    const pass = passInput ? passInput.value.trim() : '';

    if (!nome) {
      window.showToast('Preenche o nome da empresa/loja.');
      if (nomeInput) nomeInput.focus();
      return;
    }

    if (!email || !window.isValidEmail(email)) {
      window.showToast('Preenche um email de login válido para o gestor.');
      if (emailInput) emailInput.focus();
      return;
    }

    if (!pass || pass.length < 6) {
      if (passInput) {
        passInput.classList.add('field-error');
        passInput.focus();
        passInput.select();
      }
      window.showToast('A palavra-passe deve ter no mínimo 6 caracteres.');
      return;
    } else if (passInput) {
      passInput.classList.remove('field-error');
    }
  }

  document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
  const stepEl = document.getElementById('wizardStep' + step);
  if (stepEl) stepEl.classList.add('active');

  const dot1 = document.getElementById('dot-1');
  const dot2 = document.getElementById('dot-2');
  if (dot1) {
    dot1.classList.toggle('done', step > 1);
    dot1.classList.toggle('current', step === 1);
  }
  if (dot2) {
    dot2.classList.toggle('current', step === 2);
  }

  const stepLabel = document.getElementById('stepLabel');
  if (stepLabel) {
    stepLabel.textContent = step === 1
      ? 'Passo 1 de 2 · Empresa e gestor'
      : 'Passo 2 de 2 · Equipa (opcional)';
  }

  const btnBack = document.getElementById('btnBackStep');
  const btnNext = document.getElementById('btnNextStep');
  const btnSubmit = document.getElementById('btnSubmitForm');

  if (btnBack) btnBack.style.display = step === 2 ? 'inline-flex' : 'none';
  if (btnNext) btnNext.style.display = step === 1 ? 'inline-flex' : 'none';
  if (btnSubmit) btnSubmit.style.display = step === 2 ? 'inline-flex' : 'none';
};

window.nextWizardStep = function nextWizardStep() {
  const activeStep = document.querySelector('.wizard-step.active');
  if (activeStep && activeStep.id === 'wizardStep1') {
    window.goToStep(2);
  }
};

window.prevWizardStep = function prevWizardStep() {
  window.goToStep(1);
};

window.togglePasswordVisibility = function togglePasswordVisibility(inputId, btn) {
  const input = typeof inputId === 'string' ? document.getElementById(inputId) : (inputId.parentElement ? inputId.parentElement.previousElementSibling : null);
  if (input) {
    if (input.type === 'password') {
      input.type = 'text';
      if (btn) btn.textContent = 'Ocultar';
    } else {
      input.type = 'password';
      if (btn) btn.textContent = 'Mostrar';
    }
  }
};

window.copiarValor = function copiarValor(btn) {
  const input = btn.parentElement ? btn.parentElement.previousElementSibling : null;
  if (input) {
    input.select();
    document.execCommand('copy');
    window.showToast('Copiado!');
  }
};

window.addFuncionarioRow = function addFuncionarioRow() {
  const idx = funcionarioSeq++;
  const list = document.getElementById('funcionarioList');
  const empty = document.getElementById('funcionarioEmptyState');
  if (empty) empty.style.display = 'none';

  if (!list) return;

  const row = document.createElement('div');
  row.className = 'funcionario-row';
  row.innerHTML = `
    <div class="funcionario-row-top">
      <input class="field-input" name="funcionarios[${idx}][name]" placeholder="Nome do funcionário" required>
      <input class="field-input" name="funcionarios[${idx}][email]" type="email" placeholder="Email de login" required>
    </div>
    <div class="funcionario-row-bottom">
      <div class="field-with-action">
        <input class="field-input" id="fn-pass-${idx}" name="funcionarios[${idx}][password]" type="password" minlength="6" placeholder="Senha (Mín 6 chars)" style="padding-right:104px;" required>
        <div class="field-inline-actions">
          <button type="button" class="field-inline-action" onclick="togglePasswordVisibility('fn-pass-${idx}', this)">Mostrar</button>
        </div>
      </div>
      <button type="button" class="funcionario-remove" title="Remover" onclick="this.closest('.funcionario-row').remove()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
  `;
  list.appendChild(row);
};

window.openViewModal = function openViewModal(barbeariaIdOrObj) {
  let b = barbeariaIdOrObj;
  if (typeof barbeariaIdOrObj !== 'object') {
    const saloes = (window.ADMIN_DATA && window.ADMIN_DATA.saloes) || [];
    b = saloes.find(x => x.id == barbeariaIdOrObj);
  }
  
  const showView = (barbearia) => {
    document.getElementById('v-nome').textContent = barbearia.name || '—';
    document.getElementById('v-municipio').textContent = `Município: ${barbearia.municipio || '—'}`;
    document.getElementById('v-dono').textContent = barbearia.gestor || '—';
    document.getElementById('v-email').textContent = barbearia.email || '—';
    document.getElementById('v-telefone').textContent = barbearia.number || '—';
    document.getElementById('v-plano').textContent = barbearia.plano ? `${Number(barbearia.plano).toLocaleString('pt-AO')} Kz/mês` : '10.000 Kz/mês';
    document.getElementById('v-status').textContent = barbearia.isactive ? 'Ativa' : 'Suspensa';

    const editBtn = document.getElementById('v-btnEdit');
    if (editBtn) {
      editBtn.onclick = () => {
        window.closeModal('viewSalonModal');
        window.openDetailModal(barbearia);
      };
    }

    const modal = document.getElementById('viewSalonModal');
    if (modal) modal.classList.add('open');
  };

  if (b) {
    showView(b);
  } else if (typeof barbeariaIdOrObj === 'number' || typeof barbeariaIdOrObj === 'string') {
    fetch(`/barbearias/${barbeariaIdOrObj}`, { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(data => { if (data && data.id) showView(data); else window.showToast('Empresa não encontrada.'); })
      .catch(() => window.showToast('Empresa não encontrada.'));
  } else {
    window.showToast('Empresa não encontrada.');
  }
};

window.openEditModal = function openEditModal(barbeariaIdOrObj) {
  let b = barbeariaIdOrObj;
  if (typeof barbeariaIdOrObj !== 'object') {
    const saloes = (window.ADMIN_DATA && window.ADMIN_DATA.saloes) || [];
    b = saloes.find(x => x.id == barbeariaIdOrObj);
  }
  if (b) {
    window.openDetailModal(b);
  } else if (typeof barbeariaIdOrObj === 'number' || typeof barbeariaIdOrObj === 'string') {
    fetch(`/barbearias/${barbeariaIdOrObj}`, { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(data => { if (data && data.id) window.openDetailModal(data); else window.showToast('Empresa não encontrada.'); })
      .catch(() => window.showToast('Empresa não encontrada.'));
  } else {
    window.showToast('Empresa não encontrada.');
  }
};

window.openDetailModal = function openDetailModal(barbearia) {
  currentDetailBarbeariaId = barbearia.id;
  const form = document.getElementById('editSalonForm');
  if (form) form.action = `/barbearias/${barbearia.id}`;

  const detailTitle = document.getElementById('detailTitle');
  if (detailTitle) detailTitle.textContent = `Editar: ${barbearia.name}`;

  document.getElementById('d-nome').value = barbearia.name || '';
  document.getElementById('d-municipio').value = barbearia.municipio || '';
  document.getElementById('d-plano').value = barbearia.plano || 10000;
  document.getElementById('d-dono').value = barbearia.gestor || '';
  document.getElementById('d-email').value = barbearia.email || '';
  document.getElementById('d-telefone').value = barbearia.number || '';
  document.getElementById('d-senha').value = '';

  // Store the plain password so the admin can reveal it
  const passPlainInput = document.getElementById('d-senha-atual');
  if (passPlainInput) {
    passPlainInput.value = barbearia.password_plain || '(não disponível)';
    passPlainInput.type = 'password';
    const passPlainBtn = document.getElementById('d-senha-atual-btn');
    if (passPlainBtn) passPlainBtn.textContent = 'Ver';
  }

  const suspendBtn = document.getElementById('detailSuspendBtn');
  const isActive = barbearia.isactive;
  const isactiveInput = document.getElementById('d-isactive');
  if (isactiveInput) isactiveInput.value = isActive ? 1 : 0;

  if (suspendBtn) {
    if (isActive) {
      suspendBtn.textContent = 'Suspender';
      suspendBtn.className = 'btn btn-danger-ghost';
    } else {
      suspendBtn.textContent = 'Reativar';
      suspendBtn.className = 'btn btn-primary';
    }
  }

  window.loadAdminEquipa(barbearia.id);

  const detailModal = document.getElementById('detailModal');
  if (detailModal) detailModal.classList.add('open');
};

window.loadAdminEquipa = function loadAdminEquipa(barbeariaId) {
  const container = document.getElementById('adminEquipaList');
  const empty = document.getElementById('adminEquipaEmpty');
  if (!container) return;

  container.innerHTML = '<div class="muted">A carregar equipa da empresa...</div>';
  if (empty) empty.style.display = 'none';

  fetch(`/barbearias/${barbeariaId}/users`, {
    headers: { 'Accept': 'application/json' }
  })
  .then(r => { if (!r.ok) throw new Error(); return r.json(); })
  .then(users => {
    container.innerHTML = '';
    if (!users || users.length === 0) {
      if (empty) empty.style.display = 'block';
      return;
    }
    users.forEach(u => {
      const div = document.createElement('div');
      div.style.cssText = 'display:flex; align-items:center; justify-content:space-between; background:var(--bg-elevated); padding:10px 14px; border-radius:12px; border:1px solid var(--border); font-size:13px; margin-bottom:8px;';
      div.innerHTML = `
        <div style="flex:1; min-width:0;">
          <div style="font-weight:600; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; color:var(--text-primary);">${u.name}</div>
          <div style="font-size:11.5px; color:var(--text-faint); text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">${u.email}</div>
        </div>
        <div style="display:flex; gap:6px; margin-left:10px; flex-shrink:0;">
          <button type="button" class="btn btn-ghost btn-sm" title="Ver funcionário" data-user='${JSON.stringify(u)}' onclick='openAdminViewEmployeeModal(JSON.parse(this.dataset.user))'>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            Ver
          </button>
          <button type="button" class="btn btn-ghost btn-sm" title="Editar funcionário" data-user='${JSON.stringify(u)}' onclick='openAdminEditEmployeeModal(JSON.parse(this.dataset.user))'>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
            Editar
          </button>
          <button type="button" class="btn btn-danger-ghost btn-sm" title="Remover funcionário" onclick="deleteAdminEmployee(${u.id})">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            Remover
          </button>
        </div>
      `;
      container.appendChild(div);
    });
  })
  .catch(() => {
    container.innerHTML = '<div class="muted" style="color:var(--danger);">Erro ao carregar equipa.</div>';
  });
};

window.openAdminViewEmployeeModal = function openAdminViewEmployeeModal(user) {
  document.getElementById('ve-nome').textContent = user.name || 'Funcionário';
  document.getElementById('ve-nome-val').textContent = user.name || '—';
  document.getElementById('ve-email-val').textContent = user.email || '—';
  document.getElementById('ve-status-val').textContent = user.isactive ? 'Ativo' : 'Inativo';

  const editBtn = document.getElementById('ve-btnEdit');
  if (editBtn) {
    editBtn.onclick = () => {
      window.closeModal('adminViewEmployeeModal');
      window.openAdminEditEmployeeModal(user);
    };
  }

  const modal = document.getElementById('adminViewEmployeeModal');
  if (modal) modal.classList.add('open');
};

window.openAdminAddEmployeeModal = function openAdminAddEmployeeModal() {
  const form = document.getElementById('adminEmployeeForm');
  if (form) form.reset();
  document.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
  document.getElementById('ae-id').value = '';
  document.getElementById('adminEmployeeModalTitle').textContent = 'Novo Funcionário';
  const emailInp = document.getElementById('ae-email');
  if (emailInp) { emailInp.readOnly = false; emailInp.style.background = ''; }
  
  const wrap = document.getElementById('ae-senha-atual-wrap');
  if (wrap) wrap.style.display = 'none';

  const modal = document.getElementById('adminEmployeeModal');
  if (modal) modal.classList.add('open');
};

window.openAdminEditEmployeeModal = function openAdminEditEmployeeModal(user) {
  const form = document.getElementById('adminEmployeeForm');
  if (form) form.reset();
  document.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
  document.getElementById('ae-id').value = user.id;
  document.getElementById('ae-nome').value = user.name || '';
  document.getElementById('ae-email').value = user.email || '';
  const emailInp = document.getElementById('ae-email');
  if (emailInp) { emailInp.readOnly = true; emailInp.style.background = 'var(--bg-main)'; }
  document.getElementById('ae-senha').value = '';
  document.getElementById('adminEmployeeModalTitle').textContent = `Editar: ${user.name}`;

  const wrap = document.getElementById('ae-senha-atual-wrap');
  if (wrap) wrap.style.display = 'block';

  const modal = document.getElementById('adminEmployeeModal');
  if (modal) modal.classList.add('open');
};

window.saveAdminEmployee = function saveAdminEmployee(e) {
  e.preventDefault();
  const userId = document.getElementById('ae-id').value;
  const nome = document.getElementById('ae-nome').value.trim();
  const email = document.getElementById('ae-email').value.trim();
  const senhaInput = document.getElementById('ae-senha');
  const senha = senhaInput ? senhaInput.value.trim() : '';

  if (!userId && (!senha || senha.length < 6)) {
    if (senhaInput) {
      senhaInput.classList.add('field-error');
      senhaInput.focus();
      senhaInput.select();
    }
    window.showToast('A palavra-passe deve ter no mínimo 6 caracteres.');
    return;
  }

  if (senha && senha.length < 6) {
    if (senhaInput) {
      senhaInput.classList.add('field-error');
      senhaInput.focus();
      senhaInput.select();
    }
    window.showToast('A palavra-passe deve ter no mínimo 6 caracteres.');
    return;
  }

  if (senhaInput) senhaInput.classList.remove('field-error');

  const method = userId ? 'PUT' : 'POST';
  const url = userId 
    ? `/barbearias/${currentDetailBarbeariaId}/users/${userId}`
    : `/barbearias/${currentDetailBarbeariaId}/users`;

  const payload = { name: nome, email: email };
  if (senha) payload.password = senha;

  fetch(url, {
    method: method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    },
    body: JSON.stringify(payload)
  })
  .then(async r => {
    const data = await r.json().catch(() => ({}));
    if (!r.ok) {
      const msg = window.parseErrorMessage(data) || 'Erro ao guardar funcionário.';
      throw new Error(msg);
    }
    return data;
  })
  .then(() => {
    window.showToast(userId ? 'Funcionário atualizado!' : 'Funcionário adicionado com sucesso!');
    window.closeModal('adminEmployeeModal');
    window.loadAdminEquipa(currentDetailBarbeariaId);
  })
  .catch(err => {
    window.showToast(err.message || 'Erro ao guardar funcionário.');
  });
};

window.deleteAdminEmployee = function deleteAdminEmployee(userId) {
  if (!confirm('Tem certeza que deseja remover este funcionário?')) return;

  fetch(`/barbearias/${currentDetailBarbeariaId}/users/${userId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    }
  })
  .then(r => {
    if (!r.ok) throw new Error('Erro ao apagar funcionário.');
    window.showToast('Funcionário removido.');
    window.loadAdminEquipa(currentDetailBarbeariaId);
  })
  .catch(err => window.showToast(err.message));
};

window.toggleActiveStatus = function toggleActiveStatus() {
  const input = document.getElementById('d-isactive');
  input.value = input.value == 1 ? 0 : 1;
  const editForm = document.getElementById('editSalonForm');
  if (editForm) editForm.submit();
};

window.parseErrorMessage = function parseErrorMessage(data) {
  if (!data) return null;
  if (data.errors) {
    const firstKey = Object.keys(data.errors)[0];
    if (firstKey && data.errors[firstKey][0]) {
      return data.errors[firstKey][0];
    }
  }
  if (data.message) return data.message;
  return null;
};

window.showToast = function showToast(msg) {
  const toast = document.getElementById('toast');
  const toastMsg = document.getElementById('toastMsg');
  if (toast && toastMsg) {
    toastMsg.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });

  const adminEmployeeForm = document.getElementById('adminEmployeeForm');
  if (adminEmployeeForm) {
    adminEmployeeForm.addEventListener('submit', window.saveAdminEmployee);
  }

  const salonForm = document.getElementById('salonForm');
  if (salonForm) {
    salonForm.addEventListener('submit', (e) => {
      const passInput = document.querySelector('#wizardStep1 input[name="password"]');
      if (passInput && passInput.value.trim().length < 6) {
        e.preventDefault();
        window.goToStep(1);
        passInput.classList.add('field-error');
        window.showToast('A palavra-passe deve ter no mínimo 6 caracteres.');
        passInput.focus();
        passInput.select();
        return false;
      }

      const step1 = document.getElementById('wizardStep1');
      if (step1 && !step1.classList.contains('active')) {
        step1.querySelectorAll('[required]').forEach(el => {
          el.setAttribute('data-was-required', 'true');
          el.removeAttribute('required');
        });
      }
    });
  }

  const btnSubmit = document.getElementById('btnSubmitForm');
  if (btnSubmit && salonForm) {
    btnSubmit.addEventListener('click', (e) => {
      e.preventDefault();

      const passInput = document.querySelector('#wizardStep1 input[name="password"]');
      if (passInput && passInput.value.trim().length < 6) {
        window.goToStep(1);
        passInput.classList.add('field-error');
        window.showToast('A palavra-passe deve ter no mínimo 6 caracteres.');
        passInput.focus();
        passInput.select();
        return;
      }

      const step1 = document.getElementById('wizardStep1');
      if (step1 && !step1.classList.contains('active')) {
        step1.querySelectorAll('[required]').forEach(el => {
          el.removeAttribute('required');
        });
      }

      salonForm.submit();
    });
  }
});
