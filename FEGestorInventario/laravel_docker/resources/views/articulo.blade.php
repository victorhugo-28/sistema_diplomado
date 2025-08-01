<!DOCTYPE html>
<html>
<head>
    <title>Lista de Artículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .table-responsive {
            margin: 20px 0;
        }
        .actions {
            white-space: nowrap;
        }
        .article-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    @include('Navbar')
    @include('editar')
    @include('creararticulo')

    <div class="container mt-5">
        <h2 class="mb-4">Lista de Artículos</h2>

        @if(isset($articulos) && count($articulos) > 0)
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearArticulo">
                <i class="fas fa-plus"></i> Nuevo Articulo
            </button>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Stock</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articulos as $articulo)
                            <tr>
                                <td>{{ $articulo['id'] }}</td>
                                <td>
                                    @if($articulo['imagen'])
                                        <img src="{{ asset('storage/' . $articulo['imagen']) }}" class="article-image" alt="Imagen">
                                    @else
                                        <i class="fas fa-image text-muted"></i>
                                    @endif
                                </td>
                                <td>{{ $articulo['nombre'] }}</td>
                                <td>
                                    <span class="badge {{ $articulo['stock'] > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $articulo['stock'] }}
                                    </span>
                                </td>
                                <td>{{ $articulo['codigo'] ?? 'N/A' }}</td>
                                <td>{{ $articulo['tipo_nombre'] ?? 'Sin tipo' }}</td>
                                <td>{{ $articulo['proveedor_nombre'] ?? 'Sin proveedor' }}</td>
                                <td>{{ $articulo['fecha'] }}</td>
                                <td class="acciones">
                                    <button 
                                        class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#formModal" 
                                        data-id="{{ $articulo['id'] }}" 
                                        data-nombre="{{ $articulo['nombre'] }}" 
                                        data-stock="{{ $articulo['stock'] }}"
                                        data-descripcion="{{ $articulo['descripcion'] }}"
                                        data-codigo="{{ $articulo['codigo'] }}"
                                        data-codigo-gener="{{ $articulo['codigo_gener'] }}"
                                        data-tipo="{{ $articulo['id_tipo'] }}"
                                        data-proveedor="{{ $articulo['id_proveedor'] }}">
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
                No hay artículos disponibles.
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
        var stock = button.getAttribute('data-stock');
        var descripcion = button.getAttribute('data-descripcion');
        var codigo = button.getAttribute('data-codigo');
        var codigoGener = button.getAttribute('data-codigo-gener');
        var tipo = button.getAttribute('data-tipo');
        var proveedor = button.getAttribute('data-proveedor');

        var form = formModal.querySelector('#form-editar');

        form.action = form.action.replace('__id__', id);

        form.querySelector('#articulonombre').value = nombre;
        form.querySelector('#articulostock').value = stock;
        form.querySelector('#articulodescripcion').value = descripcion;
        form.querySelector('#articulocodigo').value = codigo;
        form.querySelector('#articulocodigogener').value = codigoGener;
        form.querySelector('#id_tipo').value = tipo;
        form.querySelector('#id_proveedor').value = proveedor;
    });
});
</script>
</html>