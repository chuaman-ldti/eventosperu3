// assets/app.js
async function loadTable(apiPath, tableId, columns) {
  try {
    const res = await fetch(apiPath);
    const data = await res.json();

    const tbody = document.querySelector(`#${tableId} tbody`);
    tbody.innerHTML = '';

    const rows = data.data || data;
    if (!rows || !rows.length) {
      tbody.innerHTML = `<tr><td colspan="${columns.length + 2}">Sin registros</td></tr>`;
      return;
    }

    rows.forEach(row => {
      const tr = document.createElement('tr');
      tr.innerHTML = columns.map(col => `<td>${row[col] ?? ''}</td>`).join('') +
        `<td><button class="edit" data-id="${row.id}">✏️</button></td>
         <td><button class="delete" data-id="${row.id}">🗑️</button></td>`;
      tbody.appendChild(tr);
    });

  } catch (err) {
    console.error(err);
    const tbody = document.querySelector(`#${tableId} tbody`);
    if (tbody) tbody.innerHTML = `<tr><td colspan="${columns.length + 2}">Error al conectar con la API</td></tr>`;
  }
}

async function saveRecord(apiPath, data, idField) {
  const method = data[idField] ? 'PUT' : 'POST';
  const url = data[idField] ? `${apiPath}?id=${data[idField]}` : apiPath;

  const res = await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  return res.json();
}

async function deleteRecord(apiPath, id) {
  const res = await fetch(`${apiPath}?id=${id}`, { method: 'DELETE' });
  return res.json();
}

document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('#clients-table')) initClients();
  if (document.querySelector('#providers-table')) initProviders();
  if (document.querySelector('#events-table')) initEvents();
});

function initClients() {
  const api = '../api/clients.php';
  const form = document.querySelector('#clients-form');
  const idField = 'id';

  loadTable(api, 'clients-table', ['id', 'nombre', 'telefono', 'email']);

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form));
    const res = await saveRecord(api, data, idField);
    if (res.success) {
      alert('Guardado correctamente');
      form.reset();
      loadTable(api, 'clients-table', ['id', 'nombre', 'telefono', 'email']);
    } else {
      alert('Error: ' + (res.error || res.message || 'No se pudo guardar'));
    }
  });

  document.querySelector('#clients-table').addEventListener('click', async e => {
    if (e.target.classList.contains('edit')) {
      const tr = e.target.closest('tr');
      form.id.value = tr.children[0].innerText;
      form.nombre.value = tr.children[1].innerText;
      form.telefono.value = tr.children[2].innerText;
      form.email.value = tr.children[3].innerText;
    }
    if (e.target.classList.contains('delete')) {
      if (confirm('¿Eliminar registro?')) {
        const id = e.target.dataset.id;
        const resp = await deleteRecord(api, id);
        if (resp.success) {
          loadTable(api, 'clients-table', ['id', 'nombre', 'telefono', 'email']);
        } else {
          alert('No se pudo eliminar');
        }
      }
    }
  });
}

function initProviders() {
  const api = '../api/providers.php';
  const form = document.querySelector('#providers-form');
  const idField = 'id';

  loadTable(api, 'providers-table', ['id', 'nombre', 'ruc', 'direccion', 'telefono']);

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form));
    const res = await saveRecord(api, data, idField);
    if (res.success) {
      alert('Guardado correctamente');
      form.reset();
      loadTable(api, 'providers-table', ['id', 'nombre', 'ruc', 'direccion', 'telefono']);
    } else {
      alert('Error: ' + (res.error || res.message || 'No se pudo guardar'));
    }
  });

  document.querySelector('#providers-table').addEventListener('click', async e => {
    if (e.target.classList.contains('edit')) {
      const tr = e.target.closest('tr');
      form.id.value = tr.children[0].innerText;
      form.nombre.value = tr.children[1].innerText;
      form.ruc.value = tr.children[2].innerText;
      form.direccion.value = tr.children[3].innerText;
      form.telefono.value = tr.children[4].innerText;
    }
    if (e.target.classList.contains('delete')) {
      if (confirm('¿Eliminar registro?')) {
        const id = e.target.dataset.id;
        const resp = await deleteRecord(api, id);
        if (resp.success) {
          loadTable(api, 'providers-table', ['id', 'nombre', 'ruc', 'direccion', 'telefono']);
        } else {
          alert('No se pudo eliminar');
        }
      }
    }
  });
}

function initEvents() {
  const api = '../api/events.php';
  const form = document.querySelector('#events-form');
  const idField = 'id';

  loadTable(api, 'events-table', ['id', 'nombre', 'fecha', 'ubicacion', 'estado']);

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form));
    const res = await saveRecord(api, data, idField);
    if (res.success) {
      alert('Guardado correctamente');
      form.reset();
      loadTable(api, 'events-table', ['id', 'nombre', 'fecha', 'ubicacion', 'estado']);
    } else {
      alert('Error: ' + (res.error || res.message || 'No se pudo guardar'));
    }
  });

  document.querySelector('#events-table').addEventListener('click', async e => {
    if (e.target.classList.contains('edit')) {
      const tr = e.target.closest('tr');
      form.id.value = tr.children[0].innerText;
      form.nombre.value = tr.children[1].innerText;
      form.fecha.value = tr.children[2].innerText;
      form.ubicacion.value = tr.children[3].innerText;
      form.estado.value = tr.children[4].innerText;
    }
    if (e.target.classList.contains('delete')) {
      if (confirm('¿Eliminar registro?')) {
        const id = e.target.dataset.id;
        const resp = await deleteRecord(api, id);
        if (resp.success) {
          loadTable(api, 'events-table', ['id', 'nombre', 'fecha', 'ubicacion', 'estado']);
        } else {
          alert('No se pudo eliminar');
        }
      }
    }
  });
}
