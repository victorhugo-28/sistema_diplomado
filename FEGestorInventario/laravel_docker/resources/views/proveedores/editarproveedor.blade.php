<!-- Modal para Editar Proveedor -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('proveedores.editar', '__id__') }}" id="form-editar">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">
                        <i class="fas fa-edit me-2"></i>Editar Proveedor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Proveedor</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="contacto" class="form-label">Contacto</label>
                        <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Teléfono, email, etc." required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3" placeholder="Dirección completa del proveedor" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-save me-2"></i>Actualizar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>