<!-- Modal para Crear Artículo -->
<div class="modal fade" id="modalCrearArticulo" tabindex="-1" aria-labelledby="modalCrearArticulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('articulos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearArticuloLabel">
                        <i class="fas fa-box me-2"></i>Crear Nuevo Artículo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Nombre del Artículo *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ingrese el nombre del artículo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">
                                    <i class="fas fa-warehouse me-1"></i>Stock *
                                </label>
                                <input type="number" class="form-control" id="stock" name="stock" min="0" required placeholder="Cantidad en stock">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_tipo" class="form-label">
                                    <i class="fas fa-list me-1"></i>Tipo de Artículo *
                                </label>
                                <select class="form-control" id="id_tipo" name="id_tipo" required>
                                    <option value="">Seleccione un tipo</option>
                                    @if(isset($tipos) && is_array($tipos))
                                        @foreach($tipos as $tipo)
                                            <option value="{{ $tipo['id'] }}">{{ $tipo['nombre'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_proveedor" class="form-label">
                                    <i class="fas fa-truck me-1"></i>Proveedor *
                                </label>
                                <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                                    <option value="">Seleccione un proveedor</option>
                                    @if(isset($proveedores) && is_array($proveedores))
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor['id'] }}">{{ $proveedor['nombre'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left me-1"></i>Descripción
                        </label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción detallada del artículo (opcional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="imagen" class="form-label">
                            <i class="fas fa-image me-1"></i>Imagen del Artículo
                        </label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        <div class="form-text">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB
                            </small>
                        </div>
                        <!-- Preview de la imagen -->
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>

                    <!-- Información sobre códigos autogenerados -->
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Información:</strong> Los códigos del artículo (código general y código único) se generarán automáticamente al crear el artículo.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Crear Artículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para preview de imagen -->
<script>
document.getElementById('imagen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Limpiar formulario cuando se cierre el modal
document.getElementById('modalCrearArticulo').addEventListener('hidden.bs.modal', function () {
    const form = this.querySelector('form');
    form.reset();
    document.getElementById('imagePreview').style.display = 'none';
});
</script>