<!DOCTYPE html>
<html>
<head>
    <title>Lista de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #157a8c 0%, #106b87 100%); min-height: 100vh; }
        .main-container { background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(38, 13, 51, 0.1); margin: 20px; padding: 30px; }
        .page-header { background: linear-gradient(135deg, #260d33 0%, #003f69 100%); color: white; border-radius: 15px; padding: 25px; margin-bottom: 30px; }
        .page-title { font-size: 2rem; font-weight: 700; margin: 0; }
        .btn-custom-primary { background: linear-gradient(135deg, #106b87 0%, #157a8c 100%); border: none; color: white; font-weight: 600; padding: 12px 24px; border-radius: 10px; }
        .table-container { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(38, 13, 51, 0.1); margin: 25px 0; }
        .table thead th { background: #260d33; color: white; font-weight: 600; padding: 18px 15px; border: none; text-transform: uppercase; font-size: 0.85rem; }
        .table tbody tr:hover { background: rgba(38, 13, 51, 0.05); }
        .table tbody td { padding: 18px 15px; vertical-align: middle; border: none; }
        .badge-custom { padding: 8px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .badge-boleta { background: #260d33; color: white; }
        .badge-factura { background: #003f69; color: white; }
        .badge-serie, .badge-numero { background: #003f69; color: white; }
        .badge-cliente { background: #17a2b8; color: white; }
        .badge-total { background: linear-gradient(135deg, #106b87 0%, #157a8c 100%); color: white; font-weight: 700; }
        .badge-pago { background: #28a745; color: white; }
        .badge-cambio { background: #b3aca4; color: #260d33; }
        .btn-action { padding: 8px 12px; border-radius: 8px; border: none; margin: 0 2px; font-size: 0.9rem; }
        .btn-view { background: #b3aca4; color: #260d33; }
        .btn-view:hover { background: #157a8c; color: white; }
        .modal-content { border-radius: 20px; border: none; }
        .modal-header { background: linear-gradient(135deg, #260d33 0%, #003f69 100%); color: white; border-radius: 20px 20px 0 0; padding: 25px 30px; border-bottom: none; }
        .modal-body { padding: 30px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(38, 13, 51, 0.1); }
        .detail-label { font-weight: 600; color: #260d33; }
        .detail-value { color: #003f69; font-weight: 500; }
        .payment-info { background: rgba(40, 167, 69, 0.1); border-radius: 10px; padding: 15px; margin: 15px 0; border-left: 4px solid #28a745; }
        .empty-state { text-align: center; padding: 60px 20px; background: rgba(179, 172, 164, 0.1); border-radius: 20px; color: #260d33; }
    </style>
</head>
<body>
    @include('complementos.Navbar')
    @include('ventas.crearventas')

    <div class="main-container">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title"><i class="fas fa-cash-register me-3"></i>Lista de Ventas</h1>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Gestiona y visualiza todas las ventas registradas</p>
                </div>
                @if(isset($ventas) && count($ventas) > 0)
                    <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#modalCrearVenta">
                        <i class="fas fa-plus me-2"></i> Nueva Venta
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($ventas) && count($ventas) > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-file-invoice me-2"></i>Comprobante</th>
                                <th><i class="fas fa-barcode me-2"></i>Serie</th>
                                <th><i class="fas fa-sort-numeric-up me-2"></i>Número</th>
                                <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                <th><i class="fas fa-user me-2"></i>Cliente</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Total</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ventas as $index => $venta)
                                <tr>
                                    <td>
                                        <span class="fw-bold" style="color: #260d33;">
                                            #{{ $venta['id'] ?? $venta['idventa'] ?? ($index + 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $tipoTexto = $venta['tipo_comprobante_texto'] ?? 'No especificado';
                                            $claseComprobante = stripos($tipoTexto, 'boleta') !== false ? 'badge-boleta' : 'badge-factura';
                                        @endphp
                                        <span class="badge badge-custom {{ $claseComprobante }}">{{ $tipoTexto }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-serie">
                                            {{ $venta['ventaserie_comprobante'] ?? '000' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-numero">
                                            {{ $venta['ventanum_comprobante'] ?? '000' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $fecha = $venta['ventafecha_hora'] ?? null;
                                            $fechaFormateada = $fecha ? date('d/m/Y H:i', strtotime($fecha)) : 'Sin fecha';
                                        @endphp
                                        <span>{{ $fechaFormateada }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-cliente">
                                            {{ $venta['cliente_info']['nombre'] ?? 'Sin cliente' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-total">
                                            ${{ number_format(floatval($venta['ventatotal_venta'] ?? 0), 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button 
                                            class="btn btn-action btn-view btn-ver-detalle" 
                                            data-venta='@json($venta)'
                                            title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-cash-register fa-4x mb-3"></i>
                <h4 class="fw-bold mb-3">No hay ventas disponibles</h4>
                <p class="mb-4">Aún no se han registrado ventas en el sistema. ¡Comienza creando tu primera venta!</p>
                <button class="btn btn-custom-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearVenta">
                    <i class="fas fa-plus me-2"></i>Crear la primera venta
                </button>
            </div>
        @endif
    </div>

    <!-- Modal para mostrar detalle -->
    <div class="modal fade" id="modalDetalle" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>Detalle de la Venta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenidoDetalle">
                    <!-- Contenido dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom-primary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ===== BOTÓN VER DETALLE =====
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-ver-detalle')) {
                console.log('👁️ Botón ver detalle clickeado');
                
                const button = e.target.closest('.btn-ver-detalle');
                const ventaData = button.getAttribute('data-venta');
                
                try {
                    const venta = JSON.parse(ventaData);
                    console.log('📊 Venta a mostrar:', venta);
                    verDetalle(venta);
                } catch (error) {
                    console.error('❌ Error al parsear datos de venta:', error);
                    alert('Error al cargar los detalles de la venta');
                }
            }
        });
    });

    function verDetalle(venta) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
    const contenido = document.getElementById('contenidoDetalle');
    
    const totalVenta = parseFloat(venta.ventatotal_venta || 0);
    const pagoCliente = parseFloat(venta.ventapago_cliente || 0);
    const cambio = parseFloat(venta.ventacambio || 0);
    
    let html = `
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-hashtag me-2"></i>ID:</span>
            <span class="detail-value fw-bold">#${venta.id || venta.idventa || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-file-invoice me-2"></i>Tipo:</span>
            <span class="detail-value">${venta.tipo_comprobante_texto || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-barcode me-2"></i>Serie:</span>
            <span class="detail-value">${venta.ventaserie_comprobante || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-sort-numeric-up me-2"></i>Número:</span>
            <span class="detail-value">${venta.ventanum_comprobante || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-calendar me-2"></i>Fecha:</span>
            <span class="detail-value">${venta.ventafecha_hora || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-user me-2"></i>Cliente:</span>
            <span class="detail-value">${venta.cliente_info ? venta.cliente_info.nombre : 'Sin cliente'}</span>
        </div>
        
        <hr class="my-4">
        <h6 class="fw-bold mb-3" style="color: #260d33;">
            <i class="fas fa-money-bill-wave me-2"></i>Información de Pago
        </h6>
        <div class="payment-info">
            <div class="d-flex justify-content-between mb-2">
                <span class="detail-label">Total:</span>
                <span class="detail-value fw-bold">$${totalVenta.toFixed(2)}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="detail-label">Pago:</span>
                <span class="detail-value fw-bold">$${pagoCliente.toFixed(2)}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="detail-label">Cambio:</span>
                <span class="detail-value fw-bold">$${cambio.toFixed(2)}</span>
            </div>
        </div>
    `;

    if (venta.detalles && Array.isArray(venta.detalles) && venta.detalles.length > 0) {
        // Calcular totales para el resumen
        let subtotalGeneral = 0;
        let descuentoGeneral = 0;
        
        venta.detalles.forEach(detalle => {
            const cantidad = parseFloat(detalle.detalle_ventacantidad || 0);
            const precio = parseFloat(detalle.detalle_ventaprecio_venta || 0);
            const descuento = parseFloat(detalle.detalle_ventadescuento || 0);
            
            subtotalGeneral += cantidad * precio;
            descuentoGeneral += descuento;
        });
        
        const impuesto = (subtotalGeneral - descuentoGeneral) * 0.13;
        
        html += `
            <hr class="my-4">
            <h6 class="fw-bold mb-3" style="color: #260d33;">
                <i class="fas fa-list me-2"></i>Productos
            </h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Artículo</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">Descuento</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        venta.detalles.forEach(detalle => {
            const nombre = detalle.articulo_info ? detalle.articulo_info.nombre : `ID: ${detalle.idarticulo}`;
            const cantidad = parseFloat(detalle.detalle_ventacantidad || 0);
            const precio = parseFloat(detalle.detalle_ventaprecio_venta || 0);
            const descuento = parseFloat(detalle.detalle_ventadescuento || 0);
            const subtotal = (cantidad * precio) - descuento;

            html += `
                <tr>
                    <td>
                        <div class="fw-medium">${nombre}</div>
                        ${detalle.articulo_info && detalle.articulo_info.codigo ? 
                            `<small class="text-muted">Código: ${detalle.articulo_info.codigo}</small>` : ''}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark">${cantidad}</span>
                    </td>
                    <td class="text-end">$${precio.toFixed(2)}</td>
                    <td class="text-end">
                        ${descuento > 0 ? 
                            `<span class="text-danger">-$${descuento.toFixed(2)}</span>` : 
                            '<span class="text-muted">$0.00</span>'}
                    </td>
                    <td class="text-end fw-bold">$${subtotal.toFixed(2)}</td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
            
            <!-- Resumen de totales -->
            <div class="row mt-3">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Subtotal:</small>
                                <span class="fw-medium">$${subtotalGeneral.toFixed(2)}</span>
                            </div>
                            ${descuentoGeneral > 0 ? `
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Descuentos:</small>
                                <span class="text-danger fw-medium">-$${descuentoGeneral.toFixed(2)}</span>
                            </div>
                            ` : ''}
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Impuesto (13%):</small>
                                <span class="fw-medium">$${impuesto.toFixed(2)}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold text-primary">$${totalVenta.toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    contenido.innerHTML = html;
    modal.show();
}
    </script>
</body>
</html>