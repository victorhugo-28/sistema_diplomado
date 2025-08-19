<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tipos de Artículo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilotipoproducto.css') }}">
</head>
<body>
    @include('complementos.Navbar')
    @include('tipos.editartipo')
    @include('tipos.creartipo')

    <div class="main-container">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-tags me-3"></i>
                        Lista de Tipos de Artículo
                    </h1>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Gestiona y visualiza todas las categorías de productos</p>
                </div>
                @if(isset($tipos) && count($tipos) > 0)
                    <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#modalCrearTipo">
                        <i class="fas fa-plus me-2"></i> Nuevo Tipo
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

        @if(isset($tipos) && count($tipos) > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-simplified">
                        <thead>
                            <tr>
                                <th style="cursor: pointer;" onclick="ordenarTabla('id')">
                                    <i class="fas fa-hashtag me-2"></i>ID
                                    <i class="fas fa-sort ms-1" style="opacity: 0.5;"></i>
                                </th>
                                <th style="cursor: pointer;" onclick="ordenarTabla('nombre')">
                                    <i class="fas fa-tag me-2"></i>Nombre del Tipo
                                    <i class="fas fa-sort ms-1" style="opacity: 0.5;"></i>
                                </th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipos as $tipo)
                                <tr>
                                    <td>
                                        <span class="badge badge-custom badge-id">
                                            #{{ $tipo['id'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="tipo-name">
                                            {{ $tipo['nombre'] }}
                                        </div>
                                    </td>
                                    <td class="action-buttons">
                                        <button 
                                            class="btn btn-action btn-edit" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#formModal" 
                                            data-id="{{ $tipo['id'] }}" 
                                            data-nombre="{{ $tipo['nombre'] }}"
                                            title="Editar tipo de producto">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            class="btn btn-action btn-view" 
                                            onclick="verDetalleTipo({{ json_encode($tipo) }})"
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
                <i class="fas fa-tags"></i>
                <h4 class="fw-bold mb-3">No hay tipos de artículo disponibles</h4>
                <p class="mb-4">Aún no se han registrado tipos de productos en el sistema. ¡Comienza creando tu primera categoría!</p>
                <button class="btn btn-custom-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearTipo">
                    <i class="fas fa-plus me-2"></i>Crear el primer tipo
                </button>
            </div>
        @endif
    </div>

    <!-- Modal para mostrar detalle del tipo -->
    <div class="modal fade" id="modalDetalleTipo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-tag me-2"></i>
                        Detalle del Tipo de Producto
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenidoDetalleTipo">
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
    <script src="{{ asset('js/jstipoproducto.js') }}"></script>

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