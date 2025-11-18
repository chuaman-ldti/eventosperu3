<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
$cssPath = __DIR__ . '/../assets/style.css';
$cssVer = file_exists($cssPath) ? filemtime($cssPath) : time();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Eventos Perú - Eventos</title>
  <link rel="stylesheet" href="../assets/style.css?v=<?php echo $cssVer; ?>">
  <script src="../assets/app.js" defer></script>
</head>
<body>
  <header class="header">
    <div class="brand">
      <div class="logo">UTP</div>
      <div>
        <h1>Eventos Perú</h1>
        <div style="font-size:13px;opacity:0.9">Gestión de Eventos</div>
      </div>
    </div>

    <nav class="main-nav">
      <a href="menu.php" class="<?php echo ($current_page == 'menu.php') ? 'active' : ''; ?>">Menu</a>
      <a href="clients.php" class="<?php echo ($current_page == 'clients.php') ? 'active' : ''; ?>">Clientes</a>
      <a href="providers.php" class="<?php echo ($current_page == 'providers.php') ? 'active' : ''; ?>">Proveedores</a>
      <a href="events.php" class="<?php echo ($current_page == 'events.php') ? 'active' : ''; ?>">Programacion</a>
    </nav>

    <div class="user-info">
      <span class="welcome-text">Bienvenido <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
      <a href="logout.php" class="logout-link">Cerrar Sesión</a>
    </div>
  </header>

  <main class="container">
    <section class="card">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0">Eventos</h2>
        <a class="back-link" href="menu.php">🔙 Volver al Menú</a>
      </div>

      <form id="events-form" class="card" style="margin-top:12px" autocomplete="off">
        <input type="hidden" name="id">

        <div class="form-2-cols">
          <div class="form-field">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título del evento" required>
          </div>

          <div class="form-field">
            <label for="fecha">Fecha y hora</label>
            <input type="datetime-local" id="fecha" name="fecha" required>
          </div>

          <div class="form-field">
            <label for="lugar">Lugar</label>
            <input type="text" id="lugar" name="lugar" placeholder="Dónde será" required>
          </div>

          <div class="form-field">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required>
              <option value="pendiente">Pendiente</option>
              <option value="confirmado">Confirmado</option>
              <option value="cancelado">Cancelado</option>
            </select>
          </div>

          <div class="form-field">
            <label for="cliente_id">Cliente</label>
            <select id="cliente_id" name="cliente_id" required>
              <option value="">Cargando clientes...</option>
            </select>
          </div>

          <div class="form-field">
            <label for="proveedor_id">Proveedor</label>
            <select id="proveedor_id" name="proveedor_id" required>
              <option value="">Cargando proveedores...</option>
            </select>
          </div>
        </div>

        <div class="controls" style="margin-top:20px;">
          <button class="btn" type="submit">Guardar Evento</button>
          <button type="button" id="events-reset" class="btn ghost">Limpiar</button>
        </div>
      </form>

      <div class="card table-wrap" style="margin-top:10px">
        <table id="events-table" class="styled-table" aria-describedby="events-desc">
          <caption id="events-desc" class="visually-hidden">Tabla de eventos</caption>
          <thead>
            <tr>
              <th>ID</th>
              <th>Título</th>
              <th>Fecha</th>
              <th>Lugar</th>
              <th>Estado</th>
              <th>Cliente</th>
              <th>Proveedor</th>
              <th>Creado</th>
              <th></th><th></th>
            </tr>
          </thead>
          <tbody><tr><td colspan="10">Cargando...</td></tr></tbody>
        </table>
      </div>
    </section>
  </main>

  <script>
  // Rellenar selects de cliente/proveedor; acepta respuesta como array o {data:[]}
  (function(){
    async function fetchJson(url){
      const r = await fetch(url);
      const txt = await r.text();
      try { return JSON.parse(txt); } catch(e){ return null; }
    }
    function toArray(resp){ if (!resp) return []; return Array.isArray(resp) ? resp : (resp.data || []); }
    function formatDatetimeForInput(dt){ if (!dt) return ''; return dt.replace(' ', 'T').slice(0,16); }

    document.addEventListener('DOMContentLoaded', async () => {
      const clients = toArray(await fetchJson('../api/clients.php'));
      const providers = toArray(await fetchJson('../api/providers.php'));

      const clienteSel = document.getElementById('cliente_id');
      const provSel = document.getElementById('proveedor_id');

      clienteSel.innerHTML = '<option value="">--Seleccione cliente--</option>';
      clients.forEach(c => clienteSel.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.nombre}</option>`));

      provSel.innerHTML = '<option value="">--Seleccione proveedor--</option>';
      providers.forEach(p => provSel.insertAdjacentHTML('beforeend', `<option value="${p.id}">${p.nombre}</option>`));

      // Edit handler for events table (fills form) - uses same approach as clients/providers
      document.getElementById('events-table').addEventListener('click', async (e) => {
        const btn = e.target.closest && e.target.closest('button.edit-event');
        if (btn) {
          const id = btn.dataset.id;
          const res = await fetch('../api/events.php?id=' + encodeURIComponent(id));
          const txt = await res.text();
          let json;
          try { json = JSON.parse(txt); } catch(e){ json = null; }
          let row = Array.isArray(json) ? json[0] : (json && json.data ? json.data : json);
          if (!row) return alert('Registro no encontrado');
          const form = document.getElementById('events-form');
          form.querySelector('[name="id"]').value = row.id || '';
          form.querySelector('[name="titulo"]').value = row.titulo || '';
          form.querySelector('[name="fecha"]').value = formatDatetimeForInput(row.fecha || row.created_at || '');
          form.querySelector('[name="lugar"]').value = row.lugar || '';
          form.querySelector('[name="estado"]').value = row.estado || 'pendiente';
          if (form.querySelector('[name="cliente_id"]')) form.querySelector('[name="cliente_id"]').value = row.cliente_id || '';
          if (form.querySelector('[name="proveedor_id"]')) form.querySelector('[name="proveedor_id"]').value = row.proveedor_id || '';
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        const delBtn = e.target.closest && e.target.closest('button.delete-event');
        if (delBtn) {
          const id = delBtn.dataset.id;
          if (!confirm('Eliminar evento id ' + id + '?')) return;
          await fetch('../api/events.php?id=' + encodeURIComponent(id), { method: 'DELETE' });
          location.reload();
        }
      });

      // basic form submit: forward to ../api/events.php using fetch + JSON
      const form = document.getElementById('events-form');
      form.addEventListener('submit', async (ev) => {
        ev.preventDefault();
        const data = Object.fromEntries(new FormData(form).entries());
        // convert empty strings to null for FK fields
        if (data.cliente_id === '') data.cliente_id = null;
        if (data.proveedor_id === '') data.proveedor_id = null;
        // convert datetime-local to "YYYY-MM-DD HH:MM:SS" expected by backend if needed
        if (data.fecha) data.fecha = data.fecha.replace('T',' ');
        const opts = {
          method: data.id ? 'PUT' : 'POST',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify(data)
        };
        const r = await fetch('../api/events.php' + (data.id ? '?id=' + encodeURIComponent(data.id) : ''), opts);
        const txt = await r.text();
        try { const json = JSON.parse(txt); if (json && (json.success || !json.error)) { form.reset(); location.reload(); } else { alert('Error al guardar'); console.error(txt); } } catch(e){ alert('Error al guardar'); console.error(txt); }
      });

      document.getElementById('events-reset').addEventListener('click', () => form.reset());

      // initial load of events table (simple render)
      const evRes = await fetch('../api/events.php');
      const evTxt = await evRes.text();
      let evJson;
      try { evJson = JSON.parse(evTxt); } catch(e) { evJson = null; }
      const events = Array.isArray(evJson) ? evJson : (evJson && evJson.data ? evJson.data : []);
      const tbody = document.querySelector('#events-table tbody') || document.createElement('tbody');
      tbody.innerHTML = '';
      if (!events.length) {
        tbody.innerHTML = '<tr><td colspan="10">No hay registros</td></tr>';
      } else {
        events.forEach(row => {
          const tr = document.createElement('tr');
          tr.innerHTML = [
            `<td>${row.id ?? ''}</td>`,
            `<td>${row.titulo ?? ''}</td>`,
            `<td>${row.fecha ?? ''}</td>`,
            `<td>${row.lugar ?? ''}</td>`,
            `<td>${row.estado ?? ''}</td>`,
            `<td>${(clients.find(c=>c.id==row.cliente_id)||{}).nombre ?? row.cliente_id ?? ''}</td>`,
            `<td>${(providers.find(p=>p.id==row.proveedor_id)||{}).nombre ?? row.proveedor_id ?? ''}</td>`,
            `<td>${row.created_at ?? ''}</td>`,
            `<td><button class="edit-event" data-id="${row.id}">✏️</button></td>`,
            `<td><button class="delete-event" data-id="${row.id}">🗑️</button></td>`
          ].join('');
          tbody.appendChild(tr);
        });
      }
      const table = document.getElementById('events-table');
      if (!table.querySelector('tbody')) table.appendChild(tbody);
    });
  })();
  </script>
</body>
</html>