// assets/app.js
/* Proveedores: carga tabla, guardar, editar, eliminar */
const API_PROVIDERS = '../api/providers.php';
const COLUMNS = ['id','nombre','categoria','distrito','precio','reputacion','experiencia'];

async function apiFetch(url, opts = {}) {
  const res = await fetch(url, opts);
  const txt = await res.text();
  try { return JSON.parse(txt); } catch (e) { return { error: true, raw: txt, status: res.status }; }
}

function formToObj(form) {
  return Object.fromEntries(new FormData(form).entries());
}

async function loadTable() {
  const API = '../api/providers.php';
  try {
    const res = await fetch(API);
    const txt = await res.text();
    let json;
    try { json = JSON.parse(txt); } catch (e) { console.error('API no devuelve JSON válido:', txt); return; }
    const rows = json.data || [];

    const table = document.getElementById('providers-table');
    if (!table) return console.warn('Tabla no encontrada: providers-table');

    // asegurar thead existe y coincide con columnas esperadas
    const expectedCols = ['ID','Nombre','Categoria','Distrito','Precio','Reputacion','Experiencia'];
    const thead = table.querySelector('thead') || table.createTHead();
    // si el thead está vacío, construir encabezado
    if (!thead.querySelector('tr') || thead.querySelectorAll('th').length < expectedCols.length) {
      thead.innerHTML = '<tr>' + expectedCols.map(h => `<th>${h}</th>`).join('') + '<th></th><th></th></tr>';
    }

    // asegurar tbody existe
    let tbody = table.querySelector('tbody');
    if (!tbody) { tbody = document.createElement('tbody'); table.appendChild(tbody); }
    tbody.innerHTML = '';

    if (!rows.length) {
      tbody.innerHTML = '<tr><td colspan="' + (expectedCols.length + 2) + '">No hay registros</td></tr>';
      return;
    }

    // Renderizar filas: una celda por cada columna en COLUMNS (orden fijo)
    const COLUMNS = ['id','nombre','categoria','distrito','precio','reputacion','experiencia'];
    rows.forEach((r) => {
      const tr = document.createElement('tr');
      // crear celdas de datos en orden
      COLUMNS.forEach(col => {
        const td = document.createElement('td');
        // convertir null/undefined a cadena vacía
        td.textContent = (r[col] === null || r[col] === undefined) ? '' : r[col];
        tr.appendChild(td);
      });
      // celdas de acciones (siempre al final)
      const tdEdit = document.createElement('td');
      tdEdit.innerHTML = `<button class="edit" data-id="${r.id}">✏️</button>`;
      const tdDel = document.createElement('td');
      tdDel.innerHTML = `<button class="delete" data-id="${r.id}">🗑️</button>`;
      tr.appendChild(tdEdit);
      tr.appendChild(tdDel);

      tbody.appendChild(tr);
      // depuración ligera
      // console.log('fila renderizada:', r);
    });

  } catch (err) {
    console.error('loadTable error:', err);
  }
}

async function saveRecord(data) {
  const id = data.id;
  const body = JSON.stringify(data);
  if (id) {
    return apiFetch(API_PROVIDERS + '?id=' + encodeURIComponent(id), { method: 'PUT', headers: {'Content-Type':'application/json'}, body });
  } else {
    return apiFetch(API_PROVIDERS, { method: 'POST', headers: {'Content-Type':'application/json'}, body });
  }
}

async function deleteRecord(id) {
  return apiFetch(API_PROVIDERS + '?id=' + encodeURIComponent(id), { method: 'DELETE' });
}

/* Init providers handlers */
document.addEventListener('DOMContentLoaded', () => {
  // Inicializar proveedores solo si existe el formulario de proveedores
  const providersForm = document.getElementById('providers-form');
  if (providersForm) {
    const resetBtn = document.getElementById('btn-reset');
    // cargar tabla de proveedores
    loadTable();
    providersForm.addEventListener('submit', async e => {
      e.preventDefault();
      const data = formToObj(providersForm);
      if (!data.nombre || !data.categoria) { alert('Nombre y categoría son obligatorios'); return; }
      const res = await saveRecord(data);
      if (res && res.success) {
        providersForm.reset();
        loadTable();
      } else {
        alert('Error al guardar proveedor');
        console.error('save error:', res);
      }
    });
    resetBtn && resetBtn.addEventListener('click', () => {
      providersForm.reset();
      const hid = providersForm.querySelector('[name="id"]');
      if (hid) hid.value = '';
    });

    // handler de acciones en tabla de proveedores
    document.getElementById('providers-table')?.addEventListener('click', async e => {
      if (e.target.matches('button.edit')) {
        const id = e.target.dataset.id;
        const res = await apiFetch(API_PROVIDERS + '?id=' + encodeURIComponent(id));
        const row = (res && res.data) ? res.data : {};
        Object.keys(row).forEach(k => {
          const inp = providersForm.querySelector(`[name="${k}"]`);
          if (inp) inp.value = row[k] ?? '';
        });
        const hid = providersForm.querySelector('[name="id"]');
        if (hid) hid.value = row.id || id;
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      if (e.target.matches('button.delete')) {
        const id = e.target.dataset.id;
        if (!confirm('Eliminar proveedor id ' + id + '?')) return;
        const r = await deleteRecord(id);
        if (r && r.success) loadTable(); else alert('Error al eliminar');
      }
    });
  }

  // Inicializar clientes solo si existe el formulario de clientes
  const clientsForm = document.getElementById('clients-form');
  if (clientsForm) {
    const clientsReset = document.getElementById('clients-reset');
    loadClients();
    clientsForm.addEventListener('submit', async e => {
      e.preventDefault();
      const data = formToObj(clientsForm);
      if (!data.nombre || !data.telefono) { alert('Nombre y teléfono obligatorios'); return; }
      const res = await saveClient(data);
      if (res && res.success) {
        clientsForm.reset();
        loadClients();
      } else {
        alert('Error al guardar cliente');
        console.error('saveClient error:', res);
      }
    });
    clientsReset && clientsReset.addEventListener('click', () => clientsForm.reset());

    document.getElementById('clients-table')?.addEventListener('click', async (e) => {
      try {
        // soportar clicks sobre icono/emojis dentro del botón
        const btn = e.target.closest && e.target.closest('button.edit-client');
        if (btn) {
          const id = btn.dataset.id;
          if (!id) return console.warn('edit-client sin id');
          const res = await apiFetch(API_CLIENTS + '?id=' + encodeURIComponent(id));
          // aceptar {data: {...}} o respuesta directa {...} o array [...]
          let row = res && res.data ? res.data : res;
          if (Array.isArray(row) && row.length) row = row[0];
          if (!row) return alert('Registro no encontrado');

          const form = document.getElementById('clients-form');
          if (!form) return console.warn('Formulario clients-form no encontrado');
          // rellenar solo inputs existentes
          Object.keys(row).forEach(k => {
            const inp = form.querySelector(`[name="${k}"]`);
            if (!inp) return;
            if (inp.type === 'checkbox') inp.checked = !!row[k];
            else inp.value = row[k] ?? '';
          });
          // asegurar campo id oculto
          const hid = form.querySelector('[name="id"]');
          if (hid) hid.value = row.id || id;
          window.scrollTo({ top: 0, behavior: 'smooth' });
          return;
        }

        const delBtn = e.target.closest && e.target.closest('button.delete-client');
        if (delBtn) {
          const id = delBtn.dataset.id;
          if (!confirm('Eliminar cliente id ' + id + '?')) return;
          const r = await deleteClient(id);
          if (r && r.success) loadClients(); else alert('Error al eliminar');
        }
      } catch (err) {
        console.error('Error handler clients-table:', err);
        alert('Error al procesar acción de cliente. Revisa la consola.');
      }
    });
  }
});

/* Clientes: carga tabla, guardar, editar, eliminar */
const API_CLIENTS = '../api/clients.php';
const CLIENT_COLS = ['id','nombre','telefono','email'];

async function apiFetch(url, opts = {}) {
  const res = await fetch(url, opts);
  const txt = await res.text();
  try { return JSON.parse(txt); } catch(e) { return { error: true, raw: txt, status: res.status }; }
}

function formToObj(form) {
  return Object.fromEntries(new FormData(form).entries());
}

async function loadClients() {
  try {
    const res = await fetch(API_CLIENTS);
    const txt = await res.text();
    let json;
    try {
      json = JSON.parse(txt);
    } catch (e) {
      console.error('API clients no devuelve JSON válido:', txt);
      return;
    }

    // Acepta array directo o { success:..., data: [...] }
    const rows = Array.isArray(json) ? json : (json && json.data ? json.data : []);

    const table = document.getElementById('clients-table');
    if (!table) return console.warn('Tabla no encontrada: clients-table');

    let tbody = table.querySelector('tbody');
    if (!tbody) { tbody = document.createElement('tbody'); table.appendChild(tbody); }
    tbody.innerHTML = '';

    if (!rows.length) {
      tbody.innerHTML = '<tr><td colspan="6">No hay registros</td></tr>';
      return;
    }

    rows.forEach(r => {
      const tr = document.createElement('tr');
      ['id','nombre','telefono','email'].forEach(col => {
        const td = document.createElement('td');
        td.textContent = (r[col] === null || r[col] === undefined) ? '' : r[col];
        tr.appendChild(td);
      });
      const tdEdit = document.createElement('td');
      tdEdit.innerHTML = `<button class="edit-client" data-id="${r.id}">✏️</button>`;
      const tdDel = document.createElement('td');
      tdDel.innerHTML = `<button class="delete-client" data-id="${r.id}">🗑️</button>`;
      tr.appendChild(tdEdit);
      tr.appendChild(tdDel);
      tbody.appendChild(tr);
    });

  } catch (err) {
    console.error('loadClients error:', err);
  }
}

async function saveClient(data) {
  const id = data.id;
  const body = JSON.stringify(data);
  if (id) {
    return apiFetch(API_CLIENTS + '?id=' + encodeURIComponent(id), { method: 'PUT', headers: {'Content-Type':'application/json'}, body });
  } else {
    return apiFetch(API_CLIENTS, { method: 'POST', headers: {'Content-Type':'application/json'}, body });
  }
}

async function deleteClient(id) {
  return apiFetch(API_CLIENTS + '?id=' + encodeURIComponent(id), { method: 'DELETE' });
}

/* Init clients handlers */
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('clients-form');
  const resetBtn = document.getElementById('clients-reset');
  if (form) {
    loadClients();

    form.addEventListener('submit', async e => {
      e.preventDefault();
      const data = formToObj(form);
      if (!data.nombre || !data.telefono) { alert('Nombre y teléfono obligatorios'); return; }
      const res = await saveClient(data);
      if (res && res.success) {
        form.reset();
        loadClients();
      } else {
        alert('Error al guardar: ' + (res.message || JSON.stringify(res)));
        console.error('saveClient error', res);
      }
    });

    resetBtn && resetBtn.addEventListener('click', () => form.reset());

    document.getElementById('clients-table').addEventListener('click', async e => {
      if (e.target.matches('button.edit-client')) {
        const id = e.target.dataset.id;
        const res = await apiFetch(API_CLIENTS + '?id=' + encodeURIComponent(id));
        const row = (res && res.data) ? res.data : {};
        Object.keys(row).forEach(k => {
          const inp = form.querySelector(`[name="${k}"]`);
          if (inp) inp.value = row[k] ?? '';
        });
        if (form.querySelector('[name="id"]')) form.querySelector('[name="id"]').value = row.id || id;
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      if (e.target.matches('button.delete-client')) {
        const id = e.target.dataset.id;
        if (!confirm('Eliminar cliente id ' + id + '?')) return;
        const r = await deleteClient(id);
        if (r && r.success) loadClients(); else alert('Error al eliminar');
      }
    });
  }
});
