/**
 * Nguevela Beauty — User / Barbeiro Dashboard Module
 */

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : (window.USER_DASHBOARD_DATA ? window.USER_DASHBOARD_DATA.csrfToken : '');
}

function getUserDashboardData() {
  return window.USER_DASHBOARD_DATA || {
    userId: null,
    servicos: [],
    metodosPagamento: [],
    faturadoHoje: 0,
    qtdAtendimentos: 0
  };
}

let servicoSelecionado = null;
let pagamentoSelecionado = null;
let toastTimer = null;
let pendingUndo = null;

function kz(v) {
  return new Intl.NumberFormat('pt-AO', { maximumFractionDigits: 0 }).format(v) + ' Kz';
}

function horaAgora() {
  const d = new Date();
  const pad = n => String(n).padStart(2, '0');
  return `${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

window.goToView = function goToView(name) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  const target = document.getElementById('view-' + name);
  if (target) target.classList.add('active');

  document.querySelectorAll('.nav-tab').forEach(t => t.classList.toggle('active', t.dataset.view === name));
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

window.renderServiceGrid = function renderServiceGrid() {
  const grid = document.getElementById('serviceGrid');
  if (!grid) return;
  const data = getUserDashboardData();
  const servicos = data.servicos || [];

  grid.innerHTML = servicos.map(s => `
    <div class="service-card" onclick="selecionarServico(${s.id})">
      <div class="service-icon">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4 8.12 15.88M14.47 14.48 20 20M8.12 8.12 12 12"/></svg>
      </div>
      <div class="service-name">${s.nome}</div>
      <div class="service-price">${kz(s.preco)}</div>
    </div>
  `).join('');
};

window.renderPaymentGrid = function renderPaymentGrid() {
  const grid = document.getElementById('paymentGrid');
  if (!grid) return;
  const data = getUserDashboardData();
  const metodosPagamento = data.metodosPagamento || [];

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
};

window.selecionarServico = function selecionarServico(id) {
  const data = getUserDashboardData();
  servicoSelecionado = (data.servicos || []).find(s => s.id === id);
  window.irParaStep(2);
};

window.selecionarPagamento = function selecionarPagamento(id) {
  const data = getUserDashboardData();
  pagamentoSelecionado = (data.metodosPagamento || []).find(m => m.id === id);
  window.irParaStep(3);
};

window.irParaStep = function irParaStep(step) {
  const s1 = document.getElementById('step-servico');
  const s2 = document.getElementById('step-pagamento');
  const s3 = document.getElementById('step-confirmar');

  if (s1) s1.style.display = step === 1 ? 'block' : 'none';
  if (s2) s2.style.display = step === 2 ? 'block' : 'none';
  if (s3) s3.style.display = step === 3 ? 'block' : 'none';

  [1, 2, 3].forEach(n => {
    const seg = document.getElementById('seg' + n);
    const lbl = document.getElementById('lbl' + n);
    if (seg) {
      seg.classList.toggle('done', n < step);
      seg.classList.toggle('current', n <= step);
    }
    if (lbl) lbl.classList.toggle('active', n === step);
  });

  if (step === 2) window.renderPaymentGrid();
  if (step === 3 && servicoSelecionado && pagamentoSelecionado) {
    document.getElementById('sumServico').textContent = servicoSelecionado.nome;
    document.getElementById('sumPagamento').textContent = pagamentoSelecionado.nome;
    document.getElementById('sumHora').textContent = horaAgora();
    document.getElementById('sumValor').textContent = kz(servicoSelecionado.preco);
  }
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

window.voltarStep = function voltarStep(step) { window.irParaStep(step); };

window.resetFluxo = function resetFluxo() {
  servicoSelecionado = null;
  pagamentoSelecionado = null;
  window.renderServiceGrid();
  window.irParaStep(1);
};

window.confirmarAtendimento = function confirmarAtendimento() {
  if (!servicoSelecionado || !pagamentoSelecionado) return;

  const data = getUserDashboardData();
  const valor = servicoSelecionado.preco;
  const nomeServico = servicoSelecionado.nome;
  const nomePagamento = pagamentoSelecionado.nome;

  const sValor = document.getElementById('successValor');
  const sSub = document.getElementById('successSub');
  const sOverlay = document.getElementById('successOverlay');

  if (sValor) sValor.textContent = kz(valor);
  if (sSub) sSub.textContent = `${nomeServico} · ${nomePagamento}`;
  if (sOverlay) sOverlay.classList.add('show');

  fetch(`/users/${data.userId}/atendimentos`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      service_id: servicoSelecionado.id,
      pagamento_id: pagamentoSelecionado.id,
      valor: valor,
    })
  })
  .then(async res => {
    if (!res.ok) {
      const resData = await res.json().catch(() => ({}));
      throw new Error(resData.message || 'Erro ao registar');
    }
    return res.json();
  })
  .then((novoAtendimento) => {
    setTimeout(() => {
      if (sOverlay) sOverlay.classList.remove('show');
      window.resetFluxo();
      window.showToast(`Registado: ${nomeServico} · ${kz(valor)}`, {
        duration: 6000,
        undoAction: () => window.desfazerAtendimento(novoAtendimento.id, valor)
      });
      window.updateDiaStats(valor);
      window.prependHistoryItem(novoAtendimento);
    }, 1400);
  })
  .catch(error => {
    if (sOverlay) sOverlay.classList.remove('show');
    window.showToast(error.message || 'Erro ao guardar o atendimento. Tenta novamente.');
  });
};

window.prependHistoryItem = function prependHistoryItem(atendimento) {
  const data = getUserDashboardData();
  const pagId = atendimento.pagamento_id;
  const metodo = (data.metodosPagamento || []).find(m => m.id == pagId);

  let badgeClass = 'transferencia';
  let badgeLabel = 'Transferência';

  if (metodo) {
    if (metodo.nome.includes('Dinheiro')) { badgeClass = 'dinheiro'; badgeLabel = 'Dinheiro'; }
    else if (metodo.nome.includes('TPA') || metodo.nome.includes('Multicaixa')) { badgeClass = 'multicaixa'; badgeLabel = 'Multicaixa'; }
  }

  const hora = new Date().toLocaleTimeString('pt-AO', { hour: '2-digit', minute: '2-digit' });

  const html = `
    <div class="history-item" id="atend-${atendimento.id}" style="animation: fadeIn 0.3s ease">
      <div class="history-time">${hora}</div>
      <div class="history-divider"></div>
      <div class="history-info">
        <div class="history-service">${(atendimento.service && atendimento.service.name) ? atendimento.service.name : 'Serviço'}</div>
        <div class="history-meta">
          <span class="pay-badge ${badgeClass}">${badgeLabel}</span>
        </div>
      </div>
      <div class="history-value">${kz(atendimento.valor)}</div>
    </div>
  `;

  const container = document.getElementById('newHistoryItems');
  const emptyState = document.getElementById('historyEmpty');
  if (emptyState) emptyState.style.display = 'none';
  if (container) container.insertAdjacentHTML('afterbegin', html);
};

window.updateDiaStats = function updateDiaStats(novoValor) {
  const data = getUserDashboardData();
  data.faturadoHoje += novoValor;
  data.qtdAtendimentos++;

  const kFaturado = document.getElementById('kpiFaturadoHoje');
  const kTotal = document.getElementById('kpiTotalAtend');
  const kTicket = document.getElementById('kpiTicketMedio');

  if (kFaturado) kFaturado.textContent = kz(data.faturadoHoje);
  if (kTotal) kTotal.textContent = data.qtdAtendimentos;
  if (kTicket) kTicket.textContent = kz(Math.round(data.faturadoHoje / (data.qtdAtendimentos || 1)));
};

window.desfazerAtendimento = function desfazerAtendimento(id, valor) {
  const data = getUserDashboardData();
  fetch(`/users/${data.userId}/atendimentos/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    }
  })
  .then(res => {
    if (!res.ok) throw new Error();
    const el = document.getElementById(`atend-${id}`);
    if (el) el.remove();

    data.faturadoHoje = Math.max(0, data.faturadoHoje - valor);
    data.qtdAtendimentos = Math.max(0, data.qtdAtendimentos - 1);

    const kFaturado = document.getElementById('kpiFaturadoHoje');
    const kTotal = document.getElementById('kpiTotalAtend');
    const kTicket = document.getElementById('kpiTicketMedio');

    if (kFaturado) kFaturado.textContent = kz(data.faturadoHoje);
    if (kTotal) kTotal.textContent = data.qtdAtendimentos;

    const ticket = data.qtdAtendimentos > 0 ? Math.round(data.faturadoHoje / data.qtdAtendimentos) : 0;
    if (kTicket) kTicket.textContent = data.qtdAtendimentos > 0 ? kz(ticket) : '—';

    const list = document.getElementById('historyList');
    const newItems = document.getElementById('newHistoryItems');
    if ((!list || list.children.length === 0) && (!newItems || newItems.children.length === 0)) {
      const emptyState = document.getElementById('historyEmpty');
      if (emptyState) emptyState.style.display = 'block';
    }

    window.showToast('Atendimento anulado com sucesso.');
  })
  .catch(() => {
    window.showToast('Erro ao anular o atendimento.');
  });
};

window.openChangePassword = function openChangePassword() {
  window.showToast('Contacta o gestor do salão para alterar a tua palavra-passe.');
};

window.closeModal = function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.remove('open');
};

window.handleAvatarUpload = function handleAvatarUpload(event) {
  const file = event.target.files && event.target.files[0];
  if (!file) return;
  if (!file.type.startsWith('image/')) { window.showToast('Escolhe um ficheiro de imagem válido.'); return; }

  const reader = new FileReader();
  reader.onload = e => {
    const dataUrl = e.target.result;
    [document.getElementById('profileAvatar'), document.getElementById('userAvatar')].forEach(el => {
      if (el) {
        el.style.backgroundImage = `url('${dataUrl}')`;
        el.textContent = '';
      }
    });
    window.showToast('Foto atualizada localmente.');
  };
  reader.readAsDataURL(file);
};

window.showToast = function showToast(msg, opts = {}) {
  const toast = document.getElementById('toast');
  const undoBtn = document.getElementById('toastUndoBtn');
  const progress = document.getElementById('toastProgress');
  const fill = document.getElementById('toastProgressFill');
  const toastMsg = document.getElementById('toastMsg');
  const duration = opts.duration || 3000;

  pendingUndo = null;
  clearTimeout(toastTimer);

  if (toastMsg) toastMsg.textContent = msg;

  if (opts.undoAction) {
    if (undoBtn) undoBtn.classList.add('show');
    if (progress) progress.classList.add('show');
    if (fill) {
      fill.classList.remove('animate'); void fill.offsetWidth;
      fill.style.animationDuration = duration + 'ms';
      fill.classList.add('animate');
    }
    pendingUndo = opts.undoAction;
  } else {
    if (undoBtn) undoBtn.classList.remove('show');
    if (progress) progress.classList.remove('show');
    if (fill) fill.classList.remove('animate');
  }

  if (toast) toast.classList.add('show');
  toastTimer = setTimeout(() => {
    if (toast) toast.classList.remove('show');
    pendingUndo = null;
  }, duration);
};

window.handleToastUndo = function handleToastUndo() {
  if (pendingUndo) {
    const action = pendingUndo;
    pendingUndo = null;
    clearTimeout(toastTimer);
    const toast = document.getElementById('toast');
    if (toast) toast.classList.remove('show');
    action();
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const hoje = new Date();
  const dataHoje = document.getElementById('dataHoje');
  if (dataHoje) {
    dataHoje.textContent = hoje.toLocaleDateString('pt-AO', { weekday: 'long', day: '2-digit', month: 'long' });
  }
  window.renderServiceGrid();
  window.irParaStep(1);

  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('open'); });
  });
});
