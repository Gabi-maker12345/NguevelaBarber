/**
 * Nguevela Beauty — Barbearia / Salão Manager Dashboard Module
 */

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : (window.BARBEARIA_DATA ? window.BARBEARIA_DATA.csrfToken : '');
}

function getBarbeariaData() {
  return window.BARBEARIA_DATA || {
    barbeariaId: null,
    nomeSalao: 'Salão',
    atendimentos: [],
    funcionarios: [],
    servicos: [],
    pagMeta: {}
  };
}

function moneyNumber(str) {
  if (typeof str === 'number') return str;
  if (!str) return 0;
  return Number(String(str).replace(/[^\d]/g, '')) || 0;
}

function kz(val) {
  const n = typeof val === 'number' ? val : moneyNumber(val);
  return n.toLocaleString('pt-AO') + ' Kz';
}

function sumValor(arr) {
  return arr.reduce((acc, cur) => acc + moneyNumber(cur.valor), 0);
}

function initials(nome) {
  if (!nome) return 'GS';
  return nome.split(' ').filter(Boolean).slice(0, 2).map(w => w[0].toUpperCase()).join('');
}

function isValidEmail(e) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e);
}

function paymentKey(metodo) {
  if (!metodo) return 'Dinheiro físico';
  const m = metodo.toLowerCase();
  if (m.includes('tpa') || m.includes('multicaixa')) return 'Multicaixa (TPA)';
  if (m.includes('iban') || m.includes('transf')) return 'Transferência (IBAN)';
  return 'Dinheiro físico';
}

function paymentLabel(metodo) {
  const key = paymentKey(metodo);
  const data = getBarbeariaData();
  return (data.pagMeta && data.pagMeta[key]) ? data.pagMeta[key].label : key;
}

function filterByPeriod(period, refDate) {
  const ref = new Date(refDate);
  const data = getBarbeariaData();
  return (data.atendimentos || []).filter(a => {
    const d = new Date(a.data);
    if (isNaN(d.getTime())) return false;

    if (period === 'dia') {
      return d.getFullYear() === ref.getFullYear() &&
             d.getMonth() === ref.getMonth() &&
             d.getDate() === ref.getDate();
    }
    if (period === 'semana') {
      const start = new Date(ref);
      const day = start.getDay();
      const diff = start.getDate() - day + (day === 0 ? -6 : 1);
      start.setDate(diff); start.setHours(0, 0, 0, 0);
      const end = new Date(start); end.setDate(end.getDate() + 6); end.setHours(23, 59, 59, 999);
      return d >= start && d <= end;
    }
    if (period === 'mes') {
      return d.getFullYear() === ref.getFullYear() && d.getMonth() === ref.getMonth();
    }
    return true;
  });
}

const PAGE_META = {
  dashboard: { title: 'Dashboard', sub: 'Resumo financeiro e operacional do teu salão em tempo real.' },
  caixa: { title: 'Fecho de Caixa', sub: 'Histórico de faturamento e divisão por método de pagamento.' },
  relatorios: { title: 'Relatórios PDF', sub: 'Exporta relatórios financeiros detalhados com marca d\'água oficial.' },
  equipa: { title: 'Equipa', sub: 'Gere os barbeiros do salão e as suas credenciais de acesso.' },
  catalogo: { title: 'Catálogo de Serviços', sub: 'Define os serviços e preços disponíveis para a equipa.' },
  configuracoes: { title: 'Configurações', sub: 'Identidade visual do salão e dados da tua conta de gestor.' }
};

window.switchView = function switchView(view) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  const viewEl = document.getElementById('view-' + view);
  if (viewEl) viewEl.classList.add('active');

  document.querySelectorAll('.nav-item').forEach(n => n.classList.toggle('active', n.dataset.view === view));
  document.querySelectorAll('.mobile-nav-item').forEach(n => n.classList.toggle('active', n.dataset.view === view));

  const meta = PAGE_META[view];
  if (meta) {
    const pTitle = document.getElementById('pageTitle');
    const pSub = document.getElementById('pageSub');
    if (pTitle) pTitle.textContent = meta.title;
    if (pSub) pSub.textContent = meta.sub;
  }

  const topbarActions = document.getElementById('topbarActions');
  if (topbarActions) {
    topbarActions.innerHTML = '';
    if (view === 'dashboard' || view === 'caixa') {
      topbarActions.innerHTML = `<button class="btn btn-ghost" onclick="switchView('relatorios')"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>Gerar relatório</button>`;
    }
  }

  const fab = document.getElementById('mobileFab');
  if (fab) {
    fab.classList.remove('show');
    if (view === 'equipa' || view === 'catalogo') fab.classList.add('show');
  }

  if (view === 'caixa') window.renderCaixa();
  if (view === 'relatorios') window.renderRelatorio();
  if (view === 'equipa') window.renderEquipa();
  if (view === 'catalogo') window.renderCatalogo();
};

window.mobileFabAction = function mobileFabAction() {
  const active = document.querySelector('.mobile-nav-item.active');
  if (!active) return;
  if (active.dataset.view === 'equipa') window.openFuncionarioModal();
  else if (active.dataset.view === 'catalogo') window.openServicoModal();
};

/* FECHO DE CAIXA */
let caixaPeriod = 'dia';
window.setCaixaPeriod = function setCaixaPeriod(p) {
  caixaPeriod = p;
  document.querySelectorAll('#view-caixa .filter-chip').forEach(c => c.classList.toggle('active', c.dataset.period === p));
  window.renderCaixa();
};

window.renderCaixa = function renderCaixa() {
  const lista = filterByPeriod(caixaPeriod, new Date());
  const metodos = ['Dinheiro físico', 'Multicaixa (TPA)', 'Transferência (IBAN)'];
  const ids = ['caixaDinheiro', 'caixaMulticaixa', 'caixaTransferencia'];
  metodos.forEach((m, i) => {
    const el = document.getElementById(ids[i]);
    if (el) el.textContent = kz(sumValor(lista.filter(a => paymentKey(a.pagamento) === m)));
  });

  const body = document.getElementById('caixaBody');
  if (!body) return;
  body.innerHTML = '';
  const ordenada = [...lista].sort((a, b) => new Date(b.data) - new Date(a.data));
  ordenada.forEach(a => {
    const d = new Date(a.data);
    const hora = d.toLocaleTimeString('pt-AO', { hour: '2-digit', minute: '2-digit' });
    const diaLabel = caixaPeriod === 'dia' ? hora : `${d.toLocaleDateString('pt-AO')} ${hora}`;
    const tr = document.createElement('tr');
    tr.innerHTML = `<td data-label="Hora">${diaLabel}</td><td data-label="Barbeiro">${a.funcionarioNome}</td><td data-label="Serviço">${a.servicoNome}</td><td data-label="Pagamento">${paymentLabel(a.pagamento)}</td><td data-label="Valor"><span class="price-tag">${kz(a.valor)}</span></td>`;
    body.appendChild(tr);
  });
  const empty = document.getElementById('caixaEmpty');
  if (empty) empty.style.display = lista.length ? 'none' : 'block';
};

/* RELATÓRIOS PDF */
window.onReportPeriodChange = function onReportPeriodChange() {
  const p = document.getElementById('repPeriodo').value;
  document.getElementById('repDiaWrap').style.display = p === 'dia' ? '' : 'none';
  document.getElementById('repSemanaWrap').style.display = p === 'semana' ? '' : 'none';
  document.getElementById('repMesWrap').style.display = p === 'mes' ? '' : 'none';
  window.renderRelatorio();
};

window.getRelatorioData = function getRelatorioData() {
  const periodo = document.getElementById('repPeriodo') ? document.getElementById('repPeriodo').value : 'dia';
  let ref = new Date();
  if (periodo === 'dia') {
    const v = document.getElementById('repDia') ? document.getElementById('repDia').value : '';
    if (v) { const [y, m, d] = v.split('-').map(Number); ref = new Date(y, m - 1, d, 12); }
  } else if (periodo === 'semana') {
    const v = document.getElementById('repSemana') ? document.getElementById('repSemana').value : '';
    if (v) { const [y, m, d] = v.split('-').map(Number); ref = new Date(y, m - 1, d, 12); }
  } else {
    const v = document.getElementById('repMes') ? document.getElementById('repMes').value : '';
    if (v) { const [y, m] = v.split('-').map(Number); ref = new Date(y, m - 1, 15); }
  }
  return { periodo, ref, lista: filterByPeriod(periodo, ref) };
};

window.renderRelatorio = function renderRelatorio() {
  const { lista } = window.getRelatorioData();
  const faturamentoEl = document.getElementById('repFaturamento');
  const atendimentosEl = document.getElementById('repAtendimentos');
  const ticketEl = document.getElementById('repTicket');

  if (faturamentoEl) faturamentoEl.textContent = kz(sumValor(lista));
  if (atendimentosEl) atendimentosEl.textContent = lista.length;
  if (ticketEl) ticketEl.textContent = kz(lista.length ? sumValor(lista) / lista.length : 0);

  const data = getBarbeariaData();
  const pagMeta = data.pagMeta || {};

  const pagBody = document.getElementById('repPagamentoBody');
  if (pagBody) {
    pagBody.innerHTML = '';
    const total = sumValor(lista) || 1;
    Object.keys(pagMeta).forEach(key => {
      const valor = sumValor(lista.filter(a => paymentKey(a.pagamento) === key));
      const pct = Math.round((valor / total) * 100);
      const tr = document.createElement('tr');
      tr.innerHTML = `<td data-label="Método">${pagMeta[key].label}</td><td data-label="Valor">${kz(valor)}</td><td data-label="%">${sumValor(lista) ? pct : 0}%</td>`;
      pagBody.appendChild(tr);
    });
  }

  const eqBody = document.getElementById('repEquipaBody');
  if (eqBody) {
    eqBody.innerHTML = '';
    const porFunc = {};
    lista.forEach(a => {
      if (!porFunc[a.funcionarioId]) porFunc[a.funcionarioId] = { nome: a.funcionarioNome, count: 0, total: 0 };
      porFunc[a.funcionarioId].count++;
      porFunc[a.funcionarioId].total += moneyNumber(a.valor);
    });
    const eqList = Object.values(porFunc).sort((a, b) => b.total - a.total);
    if (eqList.length === 0) {
      eqBody.innerHTML = '<tr><td colspan="3" class="muted" style="padding:18px;">Sem atendimentos neste período.</td></tr>';
    } else {
      eqList.forEach(r => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td data-label="Barbeiro">${r.nome}</td><td data-label="Atendimentos">${r.count}</td><td data-label="Faturado"><span class="price-tag">${kz(r.total)}</span></td>`;
        eqBody.appendChild(tr);
      });
    }
  }
};

window.gerarRelatorioPDF = function gerarRelatorioPDF() {
  const { periodo, lista } = window.getRelatorioData();
  if (typeof window.jspdf === 'undefined') { window.showToast('Não foi possível carregar o gerador de PDF.'); return; }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  const data = getBarbeariaData();
  const nomeSalao = data.nomeSalao || 'Salão';
  const pagMeta = data.pagMeta || {};

  function applyWatermark(pdfDoc) {
    pdfDoc.setTextColor(220, 225, 235);
    pdfDoc.setFontSize(36);
    pdfDoc.setFont('helvetica', 'bold');
    pdfDoc.text('NGUEVELA BEAUTY', 25, 160, { angle: 45 });
    pdfDoc.setTextColor(0, 0, 0);
  }

  applyWatermark(doc);

  const periodoLabel = periodo === 'dia' ? 'Diário' : periodo === 'semana' ? 'Semanal' : 'Mensal';
  doc.setFont('helvetica', 'bold'); doc.setFontSize(16); doc.text(nomeSalao, 14, 20);
  doc.setFontSize(11); doc.setFont('helvetica', 'normal');
  doc.text(`Relatório financeiro · ${periodoLabel}`, 14, 27);
  doc.text(`Gerado em ${new Date().toLocaleDateString('pt-AO')} às ${new Date().toLocaleTimeString('pt-AO', { hour: '2-digit', minute: '2-digit' })}`, 14, 33);
  doc.setDrawColor(37, 99, 235); doc.line(14, 37, 196, 37);
  doc.setFont('helvetica', 'bold'); doc.setFontSize(13); doc.text('Resumo', 14, 46);
  doc.setFont('helvetica', 'normal'); doc.setFontSize(11);
  doc.text(`Faturamento total: ${kz(sumValor(lista))}`, 14, 54);
  doc.text(`Atendimentos: ${lista.length}`, 14, 61);
  doc.text(`Ticket médio: ${kz(lista.length ? sumValor(lista) / lista.length : 0)}`, 14, 68);
  let y = 80;
  doc.setFont('helvetica', 'bold'); doc.setFontSize(13); doc.text('Métodos de pagamento', 14, y); y += 8;
  doc.setFont('helvetica', 'normal'); doc.setFontSize(11);
  const total = sumValor(lista) || 1;
  Object.keys(pagMeta).forEach(key => {
    const valor = sumValor(lista.filter(a => paymentKey(a.pagamento) === key));
    doc.text(`${pagMeta[key].label}: ${kz(valor)} (${Math.round((valor / total) * 100)}%)`, 14, y); y += 7;
  });
  y += 8; doc.setFont('helvetica', 'bold'); doc.setFontSize(13); doc.text('Produtividade da equipa', 14, y); y += 8;
  doc.setFont('helvetica', 'normal'); doc.setFontSize(11);
  const porFunc = {};
  lista.forEach(a => { if (!porFunc[a.funcionarioId]) porFunc[a.funcionarioId] = { nome: a.funcionarioNome, count: 0, total: 0 }; porFunc[a.funcionarioId].count++; porFunc[a.funcionarioId].total += moneyNumber(a.valor); });
  Object.values(porFunc).sort((a, b) => b.total - a.total).forEach(r => {
    if (y > 275) {
      doc.addPage();
      applyWatermark(doc);
      y = 20;
    }
    doc.text(`${r.nome} — ${r.count} atendimento(s) — ${kz(r.total)}`, 14, y);
    y += 7;
  });
  doc.save(`relatorio-${periodo}-${new Date().toISOString().slice(0, 10)}.pdf`);
  window.showToast('Relatório PDF gerado com sucesso.');
};

/* EQUIPA */
window.renderEquipa = function renderEquipa() {
  const body = document.getElementById('equipaBody');
  if (!body) return;
  body.innerHTML = '';
  const searchInput = document.getElementById('equipaSearch');
  const termo = (searchInput ? searchInput.value : '').trim().toLowerCase();
  const data = getBarbeariaData();
  const funcionarios = data.funcionarios || [];
  const lista = termo ? funcionarios.filter(f => f.nome.toLowerCase().includes(termo) || f.email.toLowerCase().includes(termo)) : funcionarios;

  const empty = document.getElementById('equipaEmpty');
  if (empty) empty.style.display = lista.length ? 'none' : 'block';
  if (!lista.length) return;

  lista.forEach(f => {
    const count = f.atendimentosMes || 0;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td data-label="Funcionário" class="cell-person">
        <div class="person-cell">
          <div class="person-avatar">${initials(f.nome)}</div>
          <div><div class="person-name">${f.nome}</div></div>
        </div>
      </td>
      <td data-label="Login">${f.email}</td>
      <td data-label="Atendimentos">${count}</td>
      <td data-label="Status"><span class="status-badge ${f.status}"><span class="status-dot"></span>${f.status === 'ativo' ? 'Ativo' : 'Inativo'}</span></td>
      <td data-label="" class="cell-actions">
        <div style="display:flex; gap:6px; justify-content:flex-end;">
          <button type="button" class="btn btn-ghost btn-sm" title="Ver funcionário" onclick="openViewFuncionarioModal(${f.id})">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            Ver
          </button>
          <button type="button" class="btn btn-ghost btn-sm" title="Editar funcionário" onclick="openFuncionarioModal(${f.id})">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
            Editar
          </button>
          <button type="button" class="btn btn-danger-ghost btn-sm" title="Remover funcionário" onclick="confirmRemoveFuncionario(${f.id})">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
            Remover
          </button>
        </div>
      </td>`;
    body.appendChild(tr);
  });
};

window.openViewFuncionarioModal = function openViewFuncionarioModal(id) {
  const data = getBarbeariaData();
  const funcionarios = data.funcionarios || [];
  const f = funcionarios.find(x => x.id == id);
  if (!f) { window.showToast('Funcionário não encontrado.'); return; }

  document.getElementById('vf-nome').textContent = f.nome || 'Funcionário';
  document.getElementById('vf-nome-val').textContent = f.nome || '—';
  document.getElementById('vf-email-val').textContent = f.email || '—';
  document.getElementById('vf-atend-val').textContent = `${f.atendimentosMes || 0} atendimentos`;
  document.getElementById('vf-status-val').textContent = f.status === 'ativo' ? 'Ativo' : 'Inativo';

  const editBtn = document.getElementById('vf-btnEdit');
  if (editBtn) {
    editBtn.onclick = () => {
      window.closeModal('viewFuncionarioModal');
      window.openFuncionarioModal(id);
    };
  }

  const modal = document.getElementById('viewFuncionarioModal');
  if (modal) modal.classList.add('open');
};

let editingFuncionarioId = null;
window.openFuncionarioModal = function openFuncionarioModal(id) {
  editingFuncionarioId = id || null;
  const title = document.getElementById('funcionarioModalTitle');
  const data = getBarbeariaData();
  const funcionarios = data.funcionarios || [];
  const passWrap = document.getElementById('fn-senha-atual-wrap');
  const passLabel = document.getElementById('fn-senha-label');

  const emailInput = document.getElementById('fn-email');

  if (id) {
    const f = funcionarios.find(x => x.id == id);
    if (title) title.textContent = `Editar: ${f ? f.nome : 'Funcionário'}`;
    if (f) {
      document.getElementById('fn-nome').value = f.nome || '';
      if (emailInput) {
        emailInput.value = f.email || '';
        emailInput.readOnly = true;
        emailInput.style.background = 'var(--bg-main)';
      }
    }
    document.getElementById('fn-senha').value = '';
    if (passWrap) passWrap.style.display = 'block';
    if (passLabel) passLabel.textContent = 'NOVA PALAVRA-PASSE (OPCIONAL - MÍN. 6 CHARS)';
  } else {
    if (title) title.textContent = 'Novo funcionário';
    document.getElementById('fn-nome').value = '';
    if (emailInput) {
      emailInput.value = '';
      emailInput.readOnly = false;
      emailInput.style.background = '';
    }
    document.getElementById('fn-senha').value = '';
    if (passWrap) passWrap.style.display = 'none';
    if (passLabel) passLabel.textContent = 'PALAVRA-PASSE DE ACESSO (MÍN. 6 CHARS)';
  }
  const modal = document.getElementById('funcionarioModal');
  if (modal) modal.classList.add('open');
};

window.saveFuncionario = function saveFuncionario() {
  const nomeInput = document.getElementById('fn-nome');
  const emailInput = document.getElementById('fn-email');
  const senhaInput = document.getElementById('fn-senha');

  const nome = nomeInput ? nomeInput.value.trim() : '';
  const email = emailInput ? emailInput.value.trim() : '';
  const senha = senhaInput ? senhaInput.value.trim() : '';

  if (!nome || !email) {
    window.showToast('Preenche nome e email antes de guardar.');
    if (nomeInput && !nome) nomeInput.focus();
    else if (emailInput && !email) emailInput.focus();
    return;
  }
  if (!editingFuncionarioId && (!senha || senha.length < 6)) {
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
  if (!isValidEmail(email)) {
    window.showToast('Introduz um email de login válido.');
    if (emailInput) emailInput.focus();
    return;
  }

  if (senhaInput) senhaInput.classList.remove('field-error');

  const data = getBarbeariaData();
  const barbeariaId = data.barbeariaId;

  const method = editingFuncionarioId ? 'PUT' : 'POST';
  const url = editingFuncionarioId
    ? `/barbearias/${barbeariaId}/users/${editingFuncionarioId}`
    : `/barbearias/${barbeariaId}/users`;

  fetch(url, {
    method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    },
    body: JSON.stringify({ name: nome, email, password: senha || undefined })
  })
  .then(async r => {
    const resData = await r.json().catch(() => ({}));
    if (!r.ok) {
      const msg = window.parseErrorMessage(resData) || 'Erro ao guardar funcionário.';
      throw new Error(msg);
    }
    return resData;
  })
  .then(resData => {
    if (editingFuncionarioId) {
      const f = (data.funcionarios || []).find(x => x.id === editingFuncionarioId);
      if (f) { f.nome = nome; f.email = email; }
      window.showToast(`Dados de ${nome} atualizados com sucesso!`);
    } else {
      data.funcionarios = data.funcionarios || [];
      data.funcionarios.push({ id: resData.id || Date.now(), nome, email, senha: '••••••', status: 'ativo', atendimentosMes: 0 });
      window.showToast(`${nome} adicionado à equipa com sucesso!`);
    }
    window.closeModal('funcionarioModal');
    window.renderEquipa();
  })
  .catch(err => window.showToast(err.message || 'Erro ao guardar. Tenta novamente.'));
};

window.toggleFuncionarioStatus = function toggleFuncionarioStatus(id) {
  const data = getBarbeariaData();
  const f = (data.funcionarios || []).find(x => x.id === id);
  if (!f) return;
  const novoStatus = f.status === 'ativo' ? false : true;
  fetch(`/barbearias/${data.barbeariaId}/users/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    },
    body: JSON.stringify({ isactive: novoStatus })
  })
  .then(r => { if (!r.ok) throw new Error(); return r.json(); })
  .then(() => {
    f.status = novoStatus ? 'ativo' : 'inativo';
    window.renderEquipa();
    window.showToast(`${f.nome} agora está ${f.status}.`);
  })
  .catch(() => window.showToast('Erro ao alterar estado.'));
};

window.confirmRemoveFuncionario = function confirmRemoveFuncionario(id) {
  const data = getBarbeariaData();
  const f = (data.funcionarios || []).find(x => x.id === id);
  if (!f) return;

  document.getElementById('confirmTitle').textContent = 'Remover funcionário';
  document.getElementById('confirmDesc').textContent = `Tens a certeza que queres remover ${f.nome}?`;
  document.getElementById('confirmActionBtn').onclick = () => {
    fetch(`/barbearias/${data.barbeariaId}/users/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json'
      }
    })
    .then(r => { if (!r.ok) throw new Error(); })
    .then(() => {
      data.funcionarios = (data.funcionarios || []).filter(x => x.id !== id);
      window.closeModal('confirmModal');
      window.renderEquipa();
      window.showToast(`${f.nome} removido da equipa.`);
    })
    .catch(() => window.showToast('Erro ao remover.'));
  };
  document.getElementById('confirmModal').classList.add('open');
};

/* CATÁLOGO */
window.renderCatalogo = function renderCatalogo() {
  const grid = document.getElementById('catalogoGrid');
  if (!grid) return;
  grid.innerHTML = '';
  const searchInput = document.getElementById('catalogoSearch');
  const termo = (searchInput ? searchInput.value : '').trim().toLowerCase();
  const data = getBarbeariaData();
  const servicos = data.servicos || [];
  const lista = termo ? servicos.filter(s => s.nome.toLowerCase().includes(termo)) : servicos;

  const empty = document.getElementById('catalogoEmpty');
  if (empty) empty.style.display = lista.length ? 'none' : 'block';
  if (!lista.length) return;

  lista.forEach(s => {
    const card = document.createElement('div');
    card.className = 'catalog-card';
    card.innerHTML = `
      <div class="catalog-card-top"><div class="catalog-name">${s.nome}</div></div>
      <div class="catalog-price">${kz(s.preco)}</div>
      <div class="catalog-actions">
        <button class="btn btn-ghost btn-sm" onclick="openServicoModal(${s.id})">Editar</button>
        <button class="btn btn-danger-ghost btn-sm" onclick="confirmRemoveServico(${s.id})">Remover</button>
      </div>`;
    grid.appendChild(card);
  });
};

let editingServicoId = null;
window.openServicoModal = function openServicoModal(id) {
  editingServicoId = id || null;
  const title = document.getElementById('servicoModalTitle');
  const data = getBarbeariaData();
  const servicos = data.servicos || [];

  if (id) {
    const s = servicos.find(x => x.id == id);
    if (title) title.textContent = 'Editar serviço';
    if (s) {
      document.getElementById('sv-nome').value = s.nome || '';
      document.getElementById('sv-preco').value = s.preco || '';
    }
  } else {
    if (title) title.textContent = 'Novo serviço';
    document.getElementById('sv-nome').value = '';
    document.getElementById('sv-preco').value = '';
  }
  const modal = document.getElementById('servicoModal');
  if (modal) modal.classList.add('open');
};

window.saveServico = function saveServico() {
  const nome = document.getElementById('sv-nome').value.trim();
  const preco = Number(document.getElementById('sv-preco').value);
  if (!nome || !preco || preco <= 0) { window.showToast('Preenche o nome e um preço válido.'); return; }

  const data = getBarbeariaData();
  const method = editingServicoId ? 'PUT' : 'POST';
  const url = editingServicoId ? `/services/${editingServicoId}` : '/services';

  fetch(url, {
    method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    },
    body: JSON.stringify({ name: nome, price: preco, barbearia_id: data.barbeariaId })
  })
  .then(r => { if (!r.ok) throw new Error(); return r.json(); })
  .then(resData => {
    if (editingServicoId) {
      const s = (data.servicos || []).find(x => x.id === editingServicoId);
      if (s) { s.nome = nome; s.preco = preco; }
      window.showToast(`Serviço "${nome}" atualizado.`);
    } else {
      data.servicos = data.servicos || [];
      data.servicos.push({ id: resData.id || Date.now(), nome, preco });
      window.showToast(`Serviço "${nome}" adicionado ao catálogo.`);
    }
    window.closeModal('servicoModal');
    window.renderCatalogo();
  })
  .catch(() => window.showToast('Erro ao guardar serviço.'));
};

window.confirmRemoveServico = function confirmRemoveServico(id) {
  const data = getBarbeariaData();
  const s = (data.servicos || []).find(x => x.id === id);
  if (!s) return;

  document.getElementById('confirmTitle').textContent = 'Remover serviço';
  document.getElementById('confirmDesc').textContent = `Tens a certeza que queres remover "${s.nome}" do catálogo?`;
  document.getElementById('confirmActionBtn').onclick = () => {
    fetch(`/services/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json'
      }
    })
    .then(r => { if (!r.ok) throw new Error(); })
    .then(() => {
      data.servicos = (data.servicos || []).filter(x => x.id !== id);
      window.closeModal('confirmModal');
      window.renderCatalogo();
      window.showToast(`Serviço "${s.nome}" removido.`);
    })
    .catch(() => window.showToast('Erro ao remover serviço.'));
  };
  document.getElementById('confirmModal').classList.add('open');
};

/* CONFIGURAÇÕES */
window.saveSalonSettings = function saveSalonSettings() {
  const nome = document.getElementById('s-nome-salao').value.trim();
  if (!nome) { window.showToast('O nome do salão não pode ficar vazio.'); return; }
  const data = getBarbeariaData();

  fetch(`/barbearias/${data.barbeariaId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    },
    body: JSON.stringify({ name: nome })
  })
  .then(r => { if (!r.ok) throw new Error(); return r.json(); })
  .then(() => {
    data.nomeSalao = nome;
    const bName = document.getElementById('brandName');
    const bMark = document.getElementById('brandMark');
    if (bName) bName.textContent = nome;
    if (bMark) bMark.textContent = initials(nome);
    window.showToast('Identidade do salão atualizada.');
  })
  .catch(() => window.showToast('Erro ao guardar. Tenta novamente.'));
};

window.saveGestorSettings = function saveGestorSettings() {
  const nome = document.getElementById('s-nome').value.trim();
  if (!nome) { window.showToast('Preenche o nome antes de guardar.'); return; }
  const data = getBarbeariaData();

  fetch(`/barbearias/${data.barbeariaId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      'Accept': 'application/json'
    },
    body: JSON.stringify({ gestor: nome })
  })
  .then(r => { if (!r.ok) throw new Error(); return r.json(); })
  .then(() => {
    const aName = document.querySelector('.admin-name');
    if (aName) aName.textContent = nome;
    window.showToast('Nome do gestor atualizado.');
  })
  .catch(() => window.showToast('Erro ao guardar. Tenta novamente.'));
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

window.closeModal = function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.remove('open');
};

let toastTimer;
window.showToast = function showToast(msg) {
  const toast = document.getElementById('toast');
  const toastMsg = document.getElementById('toastMsg');
  if (toast && toastMsg) {
    toastMsg.textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 3200);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('open'); });
  });
});
