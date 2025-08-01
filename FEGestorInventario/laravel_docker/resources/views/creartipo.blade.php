<!-- Modal para Crear Tipo de Artículo -->
<div class="modal fade" id="modalCrearTipo" tabindex="-1" aria-labelledby="modalCrearTipo" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('tipos-articulo.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearTipoLabel">
                        <i class="fas fa-tags me-2"></i>Crear Tipo de Artículo
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
                        <i class="fas fa-save me-2"></i>Crear Tipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>