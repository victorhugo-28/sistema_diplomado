<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Artículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/estiloarticulos.css') }}">
</head>
<body>
    @include('complementos.Navbar')
    @include('articulos.editararticulo') <!-- Este debe ser el nuevo modal simplificado -->
    @include('articulos.creararticulo')

    <div class="main-container">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-box-open me-3"></i>
                        Lista de Artículos
                    </h1>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Gestiona y visualiza todo tu inventario de productos</p>
                </div>
                @if(isset($articulos) && count($articulos) > 0)
                    <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#modalCrearArticulo">
                        <i class="fas fa-plus me-2"></i> Nuevo Artículo
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

        @if(isset($articulos) && count($articulos) > 0)
            <!-- Vista en Tabla -->
            <div id="tableView" class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="cursor: pointer;" onclick="ordenarArticulos('id')">
                                    <i class="fas fa-hashtag me-2"></i>ID
                                    <i class="fas fa-sort ms-1" style="opacity: 0.5;"></i>
                                </th>
                                <th style="cursor: pointer;" onclick="ordenarArticulos('nombre')">
                                    <i class="fas fa-box me-2"></i>Nombre
                                    <i class="fas fa-sort ms-1" style="opacity: 0.5;"></i>
                                </th>
                                <th style="cursor: pointer;" onclick="ordenarArticulos('stock')">
                                    <i class="fas fa-cubes me-2"></i>Stock
                                    <i class="fas fa-sort ms-1" style="opacity: 0.5;"></i>
                                    <span class="ms-2">
                                        <small class="text-muted">(Click para ordenar)</small>
                                    </span>
                                </th>
                                <th><i class="fas fa-barcode me-2"></i>Código</th>
                                <th><i class="fas fa-tag me-2"></i>Tipo</th>
                                <th><i class="fas fa-truck me-2"></i>Proveedor</th>
                                <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articulos as $articulo)
                                <tr data-stock="{{ $articulo['stock'] }}">
                                    <td>
                                        <span class="badge badge-custom badge-id">
                                            #{{ $articulo['id'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="article-name-display">
                                            {{ $articulo['nombre'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="stock-badge 
                                            @if($articulo['stock'] < 6) stock-low 
                                            @elseif($articulo['stock'] <= 15) stock-medium 
                                            @else stock-high 
                                            @endif">
                                            <i class="fas fa-cubes me-1"></i>
                                            {{ $articulo['stock'] }}
                                            @if($articulo['stock'] < 6)
                                                <i class="fas fa-exclamation-triangle ms-1"></i>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="article-codigo">
                                            {{ $articulo['codigo'] ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="article-tipo">
                                            {{ $articulo['tipo_nombre'] ?? 'Sin tipo' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="article-proveedor">
                                            {{ $articulo['proveedor_nombre'] ?? 'Sin proveedor' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="article-fecha">
                                            {{ $articulo['fecha'] }}
                                        </div>
                                    </td>
                                    <td class="action-buttons">
                                        <!-- BOTÓN ACTUALIZADO PARA EDITAR -->
                                        <button class="btn btn-action btn-edit" 
                                                onclick="editarArticulo({
                                                    id: {{ $articulo['id'] }},
                                                    nombre: '{{ addslashes($articulo['nombre']) }}',
                                                    stock: {{ $articulo['stock'] }},
                                                    descripcion: '{{ addslashes($articulo['descripcion'] ?? '') }}',
                                                    codigo: '{{ $articulo['codigo'] ?? '' }}',
                                                    codigo_gener: '{{ $articulo['codigo_gener'] ?? '' }}',
                                                    fecha: '{{ $articulo['fecha'] ?? '' }}',
                                                    hora: '{{ $articulo['hora'] ?? '' }}',
                                                    id_tipo: {{ $articulo['id_tipo'] }},
                                                    id_proveedor: {{ $articulo['id_proveedor'] }},
                                                    imagen: '{{ $articulo['imagen'] ?? '' }}'
                                                })"
                                                title="Editar artículo">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-action btn-view" 
                                                onclick="verDetalleArticulo({{ json_encode($articulo) }})"
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
                <i class="fas fa-box-open"></i>
                <h4 class="fw-bold mb-3">No hay artículos disponibles</h4>
                <p class="mb-4">Aún no se han registrado artículos en el inventario. ¡Comienza creando tu primer producto!</p>
                <button class="btn btn-custom-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearArticulo">
                    <i class="fas fa-plus me-2"></i>Crear el primer artículo
                </button>
            </div>
        @endif
    </div>

    <!-- Modal para mostrar detalle del artículo -->
    <div class="modal fade" id="modalDetalleArticulo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-box me-2"></i>
                        Detalle del Artículo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenidoDetalleArticulo">
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
    <script src="{{ asset('js/jsarticulos.js') }}"></script>

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