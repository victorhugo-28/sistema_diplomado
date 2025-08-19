<!DOCTYPE html>
<html>
<head>
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/estiloclientes.css') }}">
</head>
<body>
    @include('complementos.Navbar')
    @include('clientes.editar')
    @include('clientes.crear')

    <div class="main-container">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-users me-3"></i>
                        Lista de Clientes
                    </h1>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Gestiona y visualiza todos los clientes registrados</p>
                </div>
                @if(isset($clientes) && count($clientes) > 0)
                    <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#modalCrearCliente">
                        <i class="fas fa-plus me-2"></i> Nuevo Cliente
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

        @if(isset($clientes) && count($clientes) > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Nombre</th>
                                <th><i class="fas fa-envelope me-2"></i>Correo</th>
                                <th><i class="fas fa-phone me-2"></i>Teléfono</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>
                                        <div class="client-name">
                                            {{ $cliente['nombre'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-email">
                                            <i class="fas fa-at me-1"></i>
                                            {{ $cliente['email'] ?? 'Sin email' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-phone">
                                            <i class="fas fa-phone me-1"></i>
                                            {{ $cliente['telefono'] ?? 'Sin teléfono' }}
                                        </div>
                                    </td>
                                    <td class="action-buttons">
                                        <button 
                                            class="btn btn-action btn-edit" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#formModal" 
                                            data-id="{{ $cliente['id'] }}" 
                                            data-nombre="{{ $cliente['nombre'] }}" 
                                            data-email="{{ $cliente['email'] ?? '' }}" 
                                            data-telefono="{{ $cliente['telefono'] ?? '' }}"
                                            title="Editar cliente">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            class="btn btn-action btn-view" 
                                            onclick="verDetalleCliente({{ json_encode($cliente) }})"
                                            
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
                <i class="fas fa-users"></i>
                <h4 class="fw-bold mb-3">No hay clientes disponibles</h4>
                <p class="mb-4">Aún no se han registrado clientes en el sistema. ¡Comienza creando tu primer cliente!</p>
                <button class="btn btn-custom-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearCliente">
                    <i class="fas fa-plus me-2"></i>Crear el primer cliente
                </button>
            </div>
        @endif
    </div>

    <!-- Modal para mostrar detalle del cliente -->
    <div class="modal fade" id="modalDetalleCliente" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-circle me-2"></i>
                        Detalle del Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenidoDetalleCliente">
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
    <script src="{{ asset('js/jsclientes.js') }}"></script>
</body>
</html>