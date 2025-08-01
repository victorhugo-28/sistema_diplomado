<!DOCTYPE html>
<html>
<head>
    <title>Lista de Ingresos/Compras</title>
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
    @include('crearingreso')

    <div class="container mt-5">
        <h2 class="mb-4">Lista de Ingresos/Compras</h2>

        @if(isset($ingresos) && count($ingresos) > 0)
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearIngreso">
                <i class="fas fa-plus"></i> Nuevo Ingreso
            </button>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Comprobante</th>
                            <th>Serie</th>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Total</th>
                            <th>Condición</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ingresos as $ingreso)
                            <tr>
                                <td>{{ $ingreso['id'] }}</td>
                                <td>{{ $ingreso['tipo_comprobante'] }}</td>
                                <td>{{ $ingreso['serie'] }}</td>
                                <td>{{ $ingreso['numero'] }}</td>
                                <td>{{ $ingreso['fecha'] }}</td>
                                <td>{{ $ingreso['proveedor_nombre'] ?? 'Sin proveedor' }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        ${{ number_format($ingreso['total'], 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $ingreso['condicion'] == 1 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $ingreso['condicion'] == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="acciones">
                                    <button 
                                        class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#formModal" 
                                        data-id="{{ $ingreso['id'] }}" 
                                        data-tipo-comprobante="{{ $ingreso['tipo_comprobante'] }}" 
                                        data-serie="{{ $ingreso['serie'] }}"
                                        data-numero="{{ $ingreso['numero'] }}"
                                        data-fecha="{{ $ingreso['fecha'] }}"
                                        data-impuesto="{{ $ingreso['impuesto'] }}"
                                        data-total="{{ $ingreso['total'] }}"
                                        data-proveedor="{{ $ingreso['proveedor'] }}"
                                        data-condicion="{{ $ingreso['condicion'] }}">
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
                No hay ingresos disponibles.
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
        var tipoComprobante = button.getAttribute('data-tipo-comprobante');
        var serie = button.getAttribute('data-serie');
        var numero = button.getAttribute('data-numero');
        var fecha = button.getAttribute('data-fecha');
        var impuesto = button.getAttribute('data-impuesto');
        var total = button.getAttribute('data-total');
        var proveedor = button.getAttribute('data-proveedor');
        var condicion = button.getAttribute('data-condicion');

        var form = formModal.querySelector('#form-editar');

        form.action = form.action.replace('__id__', id);

        form.querySelector('#ingresotipo_comprobante').value = tipoComprobante;
        form.querySelector('#ingresoserie_comprobante').value = serie;
        form.querySelector('#ingresonumero_comprobante').value = numero;
        form.querySelector('#ingresofecha_hora').value = fecha;
        form.querySelector('#ingresoimpuesto').value = impuesto;
        form.querySelector('#ingresototal_compra').value = total;
        form.querySelector('#idproveedor').value = proveedor;
        form.querySelector('#condicion').value = condicion;
    });
});
</script>
</html>