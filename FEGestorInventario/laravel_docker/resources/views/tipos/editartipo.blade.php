<!-- Modal para Editar Tipo de Artículo -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('tipos-articulo.editar', '__id__') }}" id="form-editar">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">
                        <i class="fas fa-edit me-2"></i>Editar Tipo de Artículo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Tipo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Electrónicos, Ropa, Hogar..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-save me-2"></i>Actualizar Tipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>