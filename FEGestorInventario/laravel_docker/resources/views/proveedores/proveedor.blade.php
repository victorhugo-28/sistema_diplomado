<!DOCTYPE html>
<html>
<head>
    <title>Lista de Proveedores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/estiloproveedores.css') }}">
</head>
<body>
    @include('complementos.Navbar')
    @include('proveedores.editarproveedor')
    @include('proveedores.crearproveedor')

    <div class="main-container">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-truck me-3"></i>
                        Lista de Proveedores
                    </h1>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Gestiona y visualiza todos los proveedores registrados</p>
                </div>
                @if(isset($proveedores) && count($proveedores) > 0)
                    <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#modalCrearProveedor">
                        <i class="fas fa-plus me-2"></i> Nuevo Proveedor
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

        @if(isset($proveedores) && count($proveedores) > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-building me-2"></i>Nombre</th>
                                <th><i class="fas fa-address-book me-2"></i>Contacto</th>
                                <th><i class="fas fa-map-marker-alt me-2"></i>Dirección</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proveedores as $proveedor)
                                <tr>
                                    <td>
                                        <span class="badge badge-custom badge-id">
                                            #{{ $proveedor['id'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="provider-name">
                                            <i class="fas fa-building me-2"></i>
                                            {{ $proveedor['nombre'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="provider-contact">
                                            @if($proveedor['contacto'])
                                                @if(filter_var($proveedor['contacto'], FILTER_VALIDATE_EMAIL))
                                                    <i class="fas fa-envelope me-1"></i>
                                                @elseif(preg_match('/^[0-9+\-\s()]+$/', $proveedor['contacto']))
                                                    <i class="fas fa-phone me-1"></i>
                                                @else
                                                    <i class="fas fa-user me-1"></i>
                                                @endif
                                                {{ $proveedor['contacto'] }}
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-minus me-1"></i>Sin contacto
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="provider-address">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            @if($proveedor['direccion'])
                                                @if(strlen($proveedor['direccion']) > 50)
                                                    {{ substr($proveedor['direccion'], 0, 47) }}...
                                                @else
                                                    {{ $proveedor['direccion'] }}
                                                @endif
                                            @else
                                                <span class="text-muted">Sin dirección</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="action-buttons">
                                        <button 
                                            class="btn btn-action btn-edit" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#formModal" 
                                            data-id="{{ $proveedor['id'] }}" 
                                            data-nombre="{{ $proveedor['nombre'] }}" 
                                            data-contacto="{{ $proveedor['contacto'] ?? '' }}" 
                                            data-direccion="{{ $proveedor['direccion'] ?? '' }}"
                                            title="Editar proveedor">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            class="btn btn-action btn-view" 
                                            onclick="verDetalleProveedor({{ json_encode($proveedor) }})"
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
                <i class="fas fa-truck"></i>
                <h4 class="fw-bold mb-3">No hay proveedores disponibles</h4>
                <p class="mb-4">Aún no se han registrado proveedores en el sistema. ¡Comienza creando tu primer proveedor!</p>
                <button class="btn btn-custom-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearProveedor">
                    <i class="fas fa-plus me-2"></i>Crear el primer proveedor
                </button>
            </div>
        @endif
    </div>

    <!-- Modal para mostrar detalle del proveedor -->
    <div class="modal fade" id="modalDetalleProveedor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-truck me-2"></i>
                        Detalle del Proveedor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenidoDetalleProveedor">
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
    <script src="{{ asset('js/jsproveedores.js') }}"></script>

    <style>
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
    </style>
</body>
</html>