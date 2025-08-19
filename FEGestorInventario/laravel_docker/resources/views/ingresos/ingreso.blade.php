<!DOCTYPE html>
<html>
<head>
    <title>Lista de Ingresos/Compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #260d33;
            --color-secondary: #003f69;
            --color-tertiary: #106b87;
            --color-quaternary: #157a8c;
            --color-accent: #b3aca4;
        }

        body {
            background: linear-gradient(135deg, var(--color-quaternary) 0%, var(--color-tertiary) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(38, 13, 51, 0.1);
            backdrop-filter: blur(10px);
            margin: 20px;
            padding: 30px;
        }

        .page-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(38, 13, 51, 0.3);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .btn-custom-primary {
            background: linear-gradient(135deg, var(--color-tertiary) 0%, var(--color-quaternary) 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 107, 135, 0.3);
        }

        .btn-custom-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 107, 135, 0.4);
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(38, 13, 51, 0.1);
            margin: 25px 0;
        }

        .table {
            margin: 0;
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--color-primary);
            color: white;
            font-weight: 600;
            padding: 18px 15px;
            border: none;
            position: relative;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(38, 13, 51, 0.1);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(38, 13, 51, 0.05);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(38, 13, 51, 0.1);
        }

        .table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border: none;
        }

        .badge-custom {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .badge-boleta { 
            background: var(--color-primary); 
            color: white; 
        }
        .badge-factura { 
            background: var(--color-secondary); 
            color: white; 
        }
        .badge-nota { 
            background: var(--color-quaternary); 
            color: white; 
        }
        .badge-recibo { 
            background: var(--color-tertiary); 
            color: white; 
        }
        .badge-guia { 
            background: var(--color-primary); 
            color: white; 
        }

        .badge-serie, .badge-numero {
            background: var(--color-secondary);
            color: white;
            border: 1px solid rgba(38, 13, 51, 0.2);
        }

        .badge-proveedor {
            background: var(--color-tertiary);
            color: white;
        }

        .badge-total {
            background: linear-gradient(135deg, var(--color-tertiary) 0%, var(--color-quaternary) 100%);
            color: white;
            font-weight: 700;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            margin: 0 2px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-edit {
            background: var(--color-secondary);
            color: white;
            box-shadow: 0 3px 10px rgba(0, 63, 105, 0.3);
        }

        .btn-edit:hover {
            background: var(--color-primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 63, 105, 0.4);
            color: white;
        }

        .btn-view {
            background: var(--color-accent);
            color: var(--color-primary);
            box-shadow: 0 3px 10px rgba(179, 172, 164, 0.3);
        }

        .btn-view:hover {
            background: var(--color-quaternary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(179, 172, 164, 0.4);
            color: white;
        }

        .alert-custom {
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(21, 122, 140, 0.1) 0%, rgba(16, 107, 135, 0.1) 100%);
            color: var(--color-primary);
            border-left: 5px solid var(--color-quaternary);
        }

        .alert-danger {
            background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
            color: #d63384;
            border-left: 5px solid #dc3545;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(179, 172, 164, 0.2) 0%, rgba(179, 172, 164, 0.1) 100%);
            color: var(--color-primary);
            border-left: 5px solid var(--color-accent);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, rgba(179, 172, 164, 0.1) 0%, rgba(21, 122, 140, 0.1) 100%);
            border-radius: 20px;
            color: var(--color-primary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(38, 13, 51, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 25px 30px;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .modal-body {
            padding: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(38, 13, 51, 0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--color-primary);
            font-size: 0.95rem;
        }

        .detail-value {
            color: var(--color-secondary);
            font-weight: 500;
        }

        .details-table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .details-table thead th {
            background: var(--color-accent);
            color: var(--color-primary);
            font-weight: 600;
            padding: 15px;
            border: none;
            font-size: 0.9rem;
        }

        .details-table tbody td {
            padding: 15px;
            border-bottom: 1px solid rgba(38, 13, 51, 0.1);
        }

        .details-table tbody tr:hover {
            background: rgba(179, 172, 164, 0.1);
        }

        .fecha-display {
            font-size: 0.85rem;
            color: var(--color-primary);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                padding: 20px;
            }
            
            .table-responsive {
                border-radius: 15px;
            }
            
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    @include('complementos.Navbar')
    @include('ingresos.editaringreso')
    @include('ingresos.crearingreso')

    <div class="main-container">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-shopping-cart me-3"></i>
                        Lista de Ingresos/Compras
                    </h1>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Gestiona y visualiza todas las compras registradas</p>
                </div>
                @if(isset($ingresos) && count($ingresos) > 0)
                    <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#modalCrearIngreso">
                        <i class="fas fa-plus me-2"></i> Nuevo Ingreso
                    </button>
                @endif
            </div>
        </div>

        <!-- Mostrar mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($ingresos) && count($ingresos) > 0)
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
                                <th><i class="fas fa-truck me-2"></i>Proveedor</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Total</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ingresos as $index => $ingreso)
                                <tr>
                                    <td>
                                        <span class="fw-bold" style="color: var(--color-primary);">
                                            #{{ $ingreso['id'] ?? $ingreso['idcompra'] ?? ($index + 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $claseComprobante = 'badge-factura';
                                            $tipoTexto = $ingreso['tipo_comprobante_texto'] ?? 'No especificado';
                                            
                                            if (stripos($tipoTexto, 'boleta') !== false) {
                                                $claseComprobante = 'badge-boleta';
                                            } elseif (stripos($tipoTexto, 'factura') !== false) {
                                                $claseComprobante = 'badge-factura';
                                            } elseif (stripos($tipoTexto, 'nota') !== false) {
                                                $claseComprobante = 'badge-nota';
                                            } elseif (stripos($tipoTexto, 'recibo') !== false) {
                                                $claseComprobante = 'badge-recibo';
                                            } elseif (stripos($tipoTexto, 'guía') !== false) {
                                                $claseComprobante = 'badge-guia';
                                            }
                                        @endphp
                                        <span class="badge badge-custom {{ $claseComprobante }}">
                                            {{ $tipoTexto }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-serie">
                                            {{ $ingreso['ingresoserie_comprobante'] ?? $ingreso['serie_comprobante'] ?? '000' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-numero">
                                            {{ $ingreso['ingresonumero_comprobante'] ?? $ingreso['numero_comprobante'] ?? '000' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $fechaCampo = $ingreso['ingresofecha_hora'] ?? $ingreso['fecha_hora'] ?? null;
                                        @endphp
                                        @if($fechaCampo)
                                            @php
                                                $fecha = $fechaCampo;
                                                try {
                                                    if (strlen($fecha) > 10) {
                                                        $fechaFormateada = date('d/m/Y H:i', strtotime($fecha));
                                                    } else {
                                                        $fechaFormateada = date('d/m/Y', strtotime($fecha));
                                                    }
                                                } catch (Exception $e) {
                                                    $fechaFormateada = $fecha;
                                                }
                                            @endphp
                                            <span class="fecha-display">{{ $fechaFormateada }}</span>
                                        @else
                                            <span class="fecha-display text-muted">Sin fecha</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-proveedor">
                                            {{ $ingreso['proveedor_nombre'] ?? 'Sin proveedor' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-custom badge-total">
                                            ${{ number_format($ingreso['ingresototal_compra'] ?? $ingreso['total_compra'] ?? 0, 2) }}
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <button 
                                        class="btn btn-action btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarIngreso"
                                        data-id="{{ $ingreso['idingreso'] ?? '' }}"
                                        data-tipo-comprobante="{{ $ingreso['ingresotipo_comprobante'] ?? '' }}"
                                        data-total="{{ $ingreso['ingresototal_compra'] ?? 0 }}"
                                        data-proveedor="{{ $ingreso['idproveedor'] ?? '' }}"
                                        data-serie="{{ $ingreso['ingresoserie_comprobante'] ?? '' }}"
                                        data-numero="{{ $ingreso['ingresonumero_comprobante'] ?? '' }}"
                                        data-fecha-hora="{{ $ingreso['ingresofecha_hora'] ?? '' }}"
                                        data-impuesto="{{ $ingreso['ingresoimpuesto'] ?? 13 }}"
                                        data-condicion="{{ $ingreso['ingresocondicion'] ?? 1 }}"
                                        title="Editar ingreso"
                                        >
                                        <i class="fas fa-edit"></i>
                                        </button>
 
                                        <button 
                                            class="btn btn-action btn-view" 
                                            onclick="verDetalle({{ json_encode($ingreso) }})"
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
                <i class="fas fa-shopping-cart"></i>
                <h4 class="fw-bold mb-3">No hay ingresos disponibles</h4>
                <p class="mb-4">Aún no se han registrado ingresos en el sistema. ¡Comienza creando tu primer registro!</p>
                <button class="btn btn-custom-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearIngreso">
                    <i class="fas fa-plus me-2"></i>Crear el primer ingreso
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
                        <i class="fas fa-info-circle me-2"></i>
                        Detalle del Ingreso
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenidoDetalle">
                    <!-- Contenido dinámico -->
                </div>
                <div class="modal-footer" style="border-top: none; padding: 20px 30px;">
                    <button type="button" class="btn btn-custom-primary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // =======================
    // MODAL DE EDICIÓN 
    // =======================
    document.addEventListener('DOMContentLoaded', function () {
    const botonesEditar = document.querySelectorAll('.btn-edit');
    
    botonesEditar.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            console.log('ID capturado:', id);
            const tipo = this.dataset.tipoComprobante;
            const serie = this.dataset.serie;
            const numero = this.dataset.numero;
            const fechaHora = this.dataset.fechaHora;
            const impuesto = this.dataset.impuesto;
            const total = this.dataset.total;
            const proveedor = this.dataset.proveedor;
            const condicion = this.dataset.condicion;

            console.log('🔍 Datos capturados:', { id, tipo, total, proveedor });

            // Obtener el formulario del modal
            const formularioEditar = document.getElementById('formEditarIngreso');
            
            if (!formularioEditar) {
                console.error('❌ Formulario de edición no encontrado');
                return;
            }

            // ✅ REEMPLAZAR __id__ CON EL ID REAL
            let actionURL = formularioEditar.action;
            console.log('URL antes:', actionURL);
            actionURL = actionURL.replace('__id__', id);
            console.log('URL después:', actionURL);
            formularioEditar.setAttribute('action', actionURL);

            // Llenar campos del formulario
            document.getElementById('editar_tipo_comprobante').value = tipo || '';
            document.getElementById('editar_proveedor').value = proveedor || '';
            document.getElementById('editar_serie').value = serie || '';
            document.getElementById('editar_numero').value = numero || '';
            document.getElementById('editar_fecha_hora').value = formatearFechaHoraLocal(fechaHora);
            document.getElementById('editar_impuesto').value = parseFloat(impuesto || 13);
            document.getElementById('editar_total').value = parseFloat(total || 0);
            document.getElementById('editar_condicion').value = condicion || '1';

            console.log('✅ Modal configurado para ingreso ID:', id);
        });
    });

    // Función auxiliar para formatear fecha
    function formatearFechaHoraLocal(isoDateTime) {
        if (!isoDateTime) {
            return new Date().toISOString().slice(0, 16);
        }
        
        try {
            const fecha = new Date(isoDateTime);
            const tzOffset = fecha.getTimezoneOffset() * 60000;
            const localISOTime = new Date(fecha - tzOffset).toISOString().slice(0, 16);
            return localISOTime;
        } catch (e) {
            console.error('Error al formatear fecha:', e);
            return new Date().toISOString().slice(0, 16);
        }
    }
});
    // =======================
    // MODAL DE DETALLE
    // =======================
    function verDetalle(ingreso) {
        const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
        const contenido = document.getElementById('contenidoDetalle');
        
        // Información básica del ingreso
        let detallesHTML = `
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-uppercase fw-bold mb-3" style="color: var(--color-primary);">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h6>
                </div>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-hashtag me-2"></i>ID:</span>
                <span class="detail-value fw-bold">#${ingreso.id || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-file-invoice me-2"></i>Tipo de Comprobante:</span>
                <span class="detail-value">${ingreso.tipo_comprobante_texto || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-barcode me-2"></i>Serie:</span>
                <span class="detail-value">${ingreso.ingresoserie_comprobante || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-sort-numeric-up me-2"></i>Número:</span>
                <span class="detail-value">${ingreso.ingresonumero_comprobante || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar me-2"></i>Fecha y Hora:</span>
                <span class="detail-value">${ingreso.ingresofecha_hora || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-truck me-2"></i>Proveedor:</span>
                <span class="detail-value">${ingreso.proveedor_nombre || 'Sin proveedor'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-percentage me-2"></i>Impuesto:</span>
                <span class="detail-value">${ingreso.ingresoimpuesto || 13}%</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-dollar-sign me-2"></i>Total:</span>
                <span class="detail-value fw-bold" style="color: var(--color-tertiary);">$${parseFloat(ingreso.ingresototal_compra || 0).toFixed(2)}</span>
            </div>
        `;

        // Mostrar detalles de artículos si existen
        if (ingreso.detalles && Array.isArray(ingreso.detalles) && ingreso.detalles.length > 0) {
            detallesHTML += `
                <hr class="my-4">
                <div class="row mb-3">
                    <div class="col-12">
                        <h6 class="text-uppercase fw-bold mb-3" style="color: var(--color-primary);">
                            <i class="fas fa-list me-2"></i>Detalle de Artículos
                        </h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table details-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-box me-2"></i>Artículo</th>
                                <th><i class="fas fa-sort-amount-up me-2"></i>Cantidad</th>
                                <th><i class="fas fa-money-bill me-2"></i>P. Compra</th>
                                <th><i class="fas fa-tag me-2"></i>P. Venta</th>
                                <th><i class="fas fa-calculator me-2"></i>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            ingreso.detalles.forEach(detalle => {
                const nombreArticulo = detalle.articulo_info ? detalle.articulo_info.nombre : `Artículo ID: ${detalle.idarticulo}`;
                const codigoArticulo = detalle.articulo_info ? detalle.articulo_info.codigo : 'N/A';
                const cantidad = parseFloat(detalle.detalle_ingresocantidad || 0);
                const precioCompra = parseFloat(detalle.detalle_ingresoprecio_compra || 0);
                const precioVenta = parseFloat(detalle.detalle_ingresoprecio_venta || 0);
                const subtotal = cantidad * precioCompra;

                detallesHTML += `
                    <tr>
                        <td>
                            <div>
                                <span class="fw-bold">${nombreArticulo}</span>
                                <br>
                                <small class="text-muted">Código: ${codigoArticulo}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-custom badge-serie">${cantidad}</span>
                        </td>
                        <td>
                            <span class="fw-bold" style="color: var(--color-secondary);">$${precioCompra.toFixed(2)}</span>
                        </td>
                        <td>
                            <span class="fw-bold" style="color: var(--color-tertiary);">$${precioVenta.toFixed(2)}</span>
                        </td>
                        <td>
                            <span class="badge badge-custom badge-total">$${subtotal.toFixed(2)}</span>
                        </td>
                    </tr>
                `;
            });

            detallesHTML += `
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            detallesHTML += `
                <hr class="my-4">
                <div class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>No hay detalles de artículos disponibles para este ingreso.</p>
                </div>
            `;
        }
        
        contenido.innerHTML = detallesHTML;
        modal.show();
    }

    // =======================
    // UTILIDADES DE UI
    // =======================

    // Auto-ocultar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bootstrapAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bootstrapAlert.close();
            }, 5000);
        });
    });

    // Animación de entrada para las filas de la tabla
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
    </script>
</body>
</html>