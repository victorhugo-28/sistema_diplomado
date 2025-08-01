<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tipos de Artículo</title>
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
    @include('creartipo')

    <div class="container mt-5">
        <h2 class="mb-4">Lista de Tipos de Artículo</h2>

        @if(isset($tipos) && count($tipos) > 0)
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearTipo">
                <i class="fas fa-plus"></i> Nuevo Tipo
            </button>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tipos as $tipo)
                            <tr>
                                <td>{{ $tipo['id'] }}</td>
                                <td>{{ $tipo['nombre'] }}</td>
                                <td class="acciones">
                                    <button 
                                        class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#formModal" 
                                        data-id="{{ $tipo['id'] }}" 
                                        data-nombre="{{ $tipo['nombre'] }}">
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
                No hay tipos de artículo disponibles.
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

        var form = formModal.querySelector('#form-editar');

        form.action = form.action.replace('__id__', id);

        form.querySelector('#nombre').value = nombre;
    });
});
</script>
</html>