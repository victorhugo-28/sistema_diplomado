<!DOCTYPE html>
<html>
<head>
    <title>Lista de Proveedores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .table-responsive {
            margin: 20px 0;
        }
        .actions {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    @include('Navbar')
    @include('editar')
    @include('crearproveedor')

    <div class="container mt-5">
        <h2 class="mb-4">Lista de Proveedores</h2>

        @if(isset($proveedores) && count($proveedores) > 0)
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearProveedor">
                <i class="fas fa-plus"></i> Nuevo Proveedor
            </button>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                            <tr>
                                <td>{{ $proveedor['id'] }}</td>
                                <td>{{ $proveedor['nombre'] }}</td>
                                <td>{{ $proveedor['contacto'] }}</td>
                                <td>{{ $proveedor['direccion'] }}</td>
                                <td class="acciones">
                                    <button 
                                        class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#formModal" 
                                        data-id="{{ $proveedor['id'] }}" 
                                        data-nombre="{{ $proveedor['nombre'] }}" 
                                        data-contacto="{{ $proveedor['contacto'] }}" 
                                        data-direccion="{{ $proveedor['direccion'] }}">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                No hay proveedores disponibles.
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var formModal = document.getElementById('formModal');

    formModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;

        var id = button.getAttribute('data-id');
        var nombre = button.getAttribute('data-nombre');
        var contacto = button.getAttribute('data-contacto');
        var direccion = button.getAttribute('data-direccion');

        var form = formModal.querySelector('#form-editar');

        form.action = form.action.replace('__id__', id);

        form.querySelector('#nombre').value = nombre;
        form.querySelector('#contacto').value = contacto;
        form.querySelector('#direccion').value = direccion;
    });
});
</script>
</html>