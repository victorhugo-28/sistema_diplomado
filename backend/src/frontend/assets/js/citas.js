const API_CITAS = "http://127.0.0.1:8000/citas";

function cargarCitas() {
  fetch(API_CITAS)
    .then(res => res.json())
    .then(citas => {
      const tbody = document.getElementById("tablaCitas");
      tbody.innerHTML = "";

      citas.forEach(c => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${c.id}</td>
          <td>${c.fecha}</td>
          <td>${c.descripcion}</td>
          <td>${c.usuario_id}</td>
          <td>
            <button class="btn btn-danger btn-sm" onclick="eliminarCita(${c.id})">Eliminar</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    });
}

function crearCita() {
    const fecha = document.getElementById("nuevaFecha").value;
    const descripcion = document.getElementById("nuevaDescripcion").value;
    const usuario_id = document.getElementById("nuevoUsuarioId").value;

    if (!fecha || !descripcion || !usuario_id) {
        return alert("Completa todos los campos");
    }

    fetch(API_CITAS, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ 
    fecha, 
    descripcion, 
    usuario_id: parseInt(usuario_id) 
    })
    }).then(() => {
        document.getElementById("nuevaFecha").value = "";
        document.getElementById("nuevaDescripcion").value = "";
        document.getElementById("nuevoUsuarioId").value = "";
        cargarCitas();
    });
}

function eliminarCita(id) {
    if (confirm("¿Eliminar esta cita?")) {
        fetch(`${API_CITAS}/${id}`, { method: "DELETE" }).then(cargarCitas);
    }
}

window.onload = cargarCitas;
