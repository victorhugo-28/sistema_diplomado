<!DOCTYPE html>
<html>
<head>
    <title>Lista de Ventas</title>
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
    @include('crearventas')

    <div class="container mt-5">
        <h2 class="mb-4">Lista de Ventas</h2>

        @if(isset($ventas) && count($ventas) > 0)
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearVenta">
                <i class="fas fa-plus"></i> Nueva Venta
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
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Pago Cliente</th>
                            <th>Cambio</th>
                            <th>Condición</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                            <tr>
                                <td>{{ $venta['idventa'] }}</td>
                                <td>{{ $venta['ventatipo_comprobante'] }}</td>
                                <td>{{ $venta['ventaserie_comprobante'] }}</td>
                                <td>{{ $venta['ventanum_comprobante'] }}</td>
                                <td>{{ $venta['ventafecha_hora'] }}</td>
                                <td>{{ $venta['cliente_nombre'] ?? 'Sin cliente' }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        ${{ number_format($venta['ventatotal_venta'], 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($venta['ventapago_cliente'])
                                        ${{ number_format($venta['ventapago_cliente'], 2) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($venta['ventacambio'])
                                        ${{ number_format($venta['ventacambio'], 2) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $venta['ventacondicion'] == 1 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $venta['ventacondicion'] == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="acciones">
                                    <button 
                                        class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#formModal" 
                                        data-id="{{ $venta['idventa'] }}" 
                                        data-tipo-comprobante="{{ $venta['ventatipo_comprobante'] }}" 
                                        data-serie="{{ $venta['ventaserie_comprobante'] }}"
                                        data-numero="{{ $venta['ventanum_comprobante'] }}"
                                        data-fecha="{{ $venta['ventafecha_hora'] }}"
                                        data-impuesto="{{ $venta['ventaimpuesto'] }}"
                                        data-total="{{ $venta['ventatotal_venta'] }}"
                                        data-cliente="{{ $venta['idcliente'] }}"
                                        data-condicion="{{ $venta['ventacondicion'] }}"
                                        data-pago="{{ $venta['ventapago_cliente'] }}"
                                        data-cambio="{{ $venta['ventacambio'] }}">
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
                No hay ventas disponibles.
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
        var cliente = button.getAttribute('data-cliente');
        var condicion = button.getAttribute('data-condicion');
        var pago = button.getAttribute('data-pago');
        var cambio = button.getAttribute('data-cambio');

        var form = formModal.querySelector('#form-editar');

        form.action = form.action.replace('__id__', id);

        form.querySelector('#ventatipo_comprobante').value = tipoComprobante;
        form.querySelector('#ventaserie_comprobante').value = serie;
        form.querySelector('#ventanum_comprobante').value = numero;
        form.querySelector('#ventafecha_hora').value = fecha;
        form.querySelector('#ventaimpuesto').value = impuesto;
        form.querySelector('#ventatotal_venta').value = total;
        form.querySelector('#idcliente').value = cliente;
        form.querySelector('#ventacondicion').value = condicion;
        form.querySelector('#ventapago_cliente').value = pago;
        form.querySelector('#ventacambio').value = cambio;
    });
});
</script>
</html>