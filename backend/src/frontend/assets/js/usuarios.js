const API = "http://127.0.0.1:8000/usuarios";

function cargarUsuarios() {
  fetch(API)
    .then(res => res.json())
    .then(usuarios => {
      const tbody = document.getElementById("tablaUsuarios");
      tbody.innerHTML = "";

      usuarios.forEach(u => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${u.id}</td>
          <td>${u.nombre}</td>
          <td>${u.correo}</td>
          <td>
            <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${u.id})">Eliminar</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    });
}

function crearUsuario() {
  const nombre = document.getElementById("nuevoNombre").value;
  const correo = document.getElementById("nuevoCorreo").value;
  if (!nombre || !correo) return alert("Completa todos los campos");

  fetch(API, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ nombre, correo })
  }).then(() => {
    document.getElementById("nuevoNombre").value = "";
    document.getElementById("nuevoCorreo").value = "";
    cargarUsuarios();
  });
}

function eliminarUsuario(id) {
  if (confirm("¿Eliminar este usuario?")) {
    fetch(`${API}/${id}`, { method: "DELETE" }).then(cargarUsuarios);
  }
}

window.onload = cargarUsuarios;
