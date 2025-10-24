<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Candidatos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input, select, button { padding: 6px; margin: 4px; }
    </style>
</head>
<body>
    <h1>Gestión de Candidatos</h1>

    <form id="formCandidato">
        <input type="hidden" id="candidatoId">
        <label>Provincia:</label>
        <select id="provincia" required>
            @foreach(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'] as $prov)
                <option value="{{ $prov }}">{{ $prov }}</option>
            @endforeach
        </select>

        <label>Cargo:</label>
        <select id="cargo" required>
            <option value="DIPUTADOS">DIPUTADOS</option>
            <option value="SENADORES">SENADORES</option>
        </select>

        <label>Lista:</label>
        <input type="text" id="lista" maxlength="20" required>

        <label>Nombre:</label>
        <input type="text" id="nombre" maxlength="255" required>

        <label>Orden en Lista:</label>
        <input type="number" id="orden_en_lista" max="10" required>

        <button type="submit">Guardar</button>
        <button type="button" id="btnReset">Limpiar</button>
    </form>

    <h2>Lista de Candidatos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Provincia</th>
                <th>Cargo</th>
                <th>Lista</th>
                <th>Nombre</th>
                <th>Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaCandidatos">
        </tbody>
    </table>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function fetchCandidatos() {
            const res = await fetch('/api/candidatos');
            const data = await res.json();
            const tbody = document.getElementById('tablaCandidatos');
            tbody.innerHTML = '';
            data.forEach(c => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${c.id}</td>
                    <td>${c.provincia}</td>
                    <td>${c.cargo}</td>
                    <td>${c.lista}</td>
                    <td>${c.nombre}</td>
                    <td>${c.orden_en_lista}</td>
                    <td>
                        <button onclick="editCandidato(${c.id})">Editar</button>
                        <button onclick="deleteCandidato(${c.id})">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function deleteCandidato(id) {
            if (!confirm('¿Eliminar candidato?')) return;
            await fetch(`/api/candidatos/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            fetchCandidatos();
        }

        async function editCandidato(id) {
            const res = await fetch(`/api/candidatos/${id}`);
            const c = await res.json();
            document.getElementById('candidatoId').value = c.id;
            document.getElementById('provincia').value = c.provincia;
            document.getElementById('cargo').value = c.cargo;
            document.getElementById('lista').value = c.lista;
            document.getElementById('nombre').value = c.nombre;
            document.getElementById('orden_en_lista').value = c.orden_en_lista;
        }

        document.getElementById('btnReset').addEventListener('click', () => {
            document.getElementById('formCandidato').reset();
            document.getElementById('candidatoId').value = '';
        });

        document.getElementById('formCandidato').addEventListener('submit', async e => {
            e.preventDefault();
            const id = document.getElementById('candidatoId').value;
            const payload = {
                provincia: document.getElementById('provincia').value,
                cargo: document.getElementById('cargo').value,
                lista: document.getElementById('lista').value,
                nombre: document.getElementById('nombre').value,
                orden_en_lista: parseInt(document.getElementById('orden_en_lista').value)
            }
            
            const url = id ? `/api/candidatos/${id}` : '/api/candidatos/store';
            const method = id ? 'PUT' : 'POST';
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const resp = await res.json();
            alert(resp.mensaje || 'Operación completada');
            document.getElementById('formCandidato').reset();
            document.getElementById('candidatoId').value = '';
            fetchCandidatos();
        });

        // Cargar tabla al inicio
        fetchCandidatos();
    </script>
</body>
</html>
