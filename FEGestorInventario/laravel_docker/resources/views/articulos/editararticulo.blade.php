<!-- Modal para Editar Artículo -->
<div class="modal fade" id="modalEditarArticulo" tabindex="-1" aria-labelledby="modalEditarArticulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-editar" method="POST" action="{{ route('articulos.editar', '__id__') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarArticuloLabel">
                        <i class="fas fa-edit me-2"></i>Editar Artículo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Campos EDITABLES por el usuario -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nombre" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Nombre del Artículo *
                                </label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required placeholder="Ingrese el nombre del artículo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_stock" class="form-label">
                                    <i class="fas fa-warehouse me-1"></i>Stock *
                                </label>
                                <input type="number" class="form-control" id="edit_stock" name="stock" min="0" required placeholder="Cantidad en stock">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_id_tipo" class="form-label">
                                    <i class="fas fa-list me-1"></i>Tipo de Artículo *
                                </label>
                                <select class="form-control" id="edit_id_tipo" name="id_tipo" required>
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
                                <label for="edit_id_proveedor" class="form-label">
                                    <i class="fas fa-truck me-1"></i>Proveedor *
                                </label>
                                <select class="form-control" id="edit_id_proveedor" name="id_proveedor" required>
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
                        <label for="edit_descripcion" class="form-label">
                            <i class="fas fa-align-left me-1"></i>Descripción
                        </label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3" placeholder="Descripción detallada del artículo (opcional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_imagen" class="form-label">
                            <i class="fas fa-image me-1"></i>Cambiar Imagen del Artículo
                        </label>
                        <input type="file" class="form-control" id="edit_imagen" name="imagen" accept="image/*">
                        <div class="form-text">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB. Solo suba una imagen si desea cambiar la actual.
                            </small>
                        </div>
                        
                        <!-- Preview de la imagen actual -->
                        <div id="currentImagePreview" class="mt-2" style="display: none;">
                            <label class="form-label small text-muted">Imagen actual:</label>
                            <br>
                            <img id="currentImg" src="" alt="Imagen actual" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                        </div>
                        
                        <!-- Preview de la nueva imagen -->
                        <div id="editImagePreview" class="mt-2" style="display: none;">
                            <label class="form-label small text-success">Nueva imagen:</label>
                            <br>
                            <img id="editPreviewImg" src="" alt="Vista previa" class="img-thumbnail border-success" style="max-width: 150px; max-height: 150px;">
                        </div>
                    </div>

                    <!-- Campos OCULTOS que se llenan pero NO se muestran al usuario -->
                    <!-- Estos campos se llenan automáticamente pero no son editables -->
                    <input type="hidden" id="edit_codigo" name="codigo">
                    <input type="hidden" id="edit_codigo_gener" name="codigo_gener">
                    <input type="hidden" id="edit_fecha" name="fecha">
                    <input type="hidden" id="edit_hora" name="hora">

                    <!-- Información visual (solo lectura) sobre datos no modificables -->
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Información:</strong> Los códigos, fecha y hora de creación no pueden ser modificados y se mantienen automáticamente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Actualizar Artículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para abrir el modal de edición con datos
function editarArticulo(articulo) {
    console.log('🔧 Iniciando edición de artículo:', articulo);
    
    // Actualizar la acción del formulario con el ID correcto
    const form = document.getElementById('form-editar');
    const action = form.action.replace('__id__', articulo.id);
    form.action = action;
    console.log('📝 Action del formulario actualizado a:', action);
    
    // ✅ Llenar campos EDITABLES (visibles al usuario)
    document.getElementById('edit_nombre').value = articulo.nombre || '';
    document.getElementById('edit_stock').value = articulo.stock || 0;
    document.getElementById('edit_descripcion').value = articulo.descripcion || '';
    
    // ✅ Seleccionar tipo y proveedor
    const tipoSelect = document.getElementById('edit_id_tipo');
    if (articulo.id_tipo) {
        tipoSelect.value = articulo.id_tipo;
        console.log('✅ Tipo seleccionado:', articulo.id_tipo);
    }
    
    const proveedorSelect = document.getElementById('edit_id_proveedor');
    if (articulo.id_proveedor) {
        proveedorSelect.value = articulo.id_proveedor;
        console.log('✅ Proveedor seleccionado:', articulo.id_proveedor);
    }
    
    // ✅ Llenar campos OCULTOS (no editables pero necesarios para el controlador)
    document.getElementById('edit_codigo').value = articulo.codigo || '';
    document.getElementById('edit_codigo_gener').value = articulo.codigo_gener || '';
    document.getElementById('edit_fecha').value = articulo.fecha || '';
    document.getElementById('edit_hora').value = articulo.hora || '';
    
    console.log('✅ Campos ocultos llenados:', {
        codigo: articulo.codigo || '',
        codigo_gener: articulo.codigo_gener || '',
        fecha: articulo.fecha || '',
        hora: articulo.hora || ''
    });
    
    // ✅ Mostrar imagen actual si existe
    const currentImagePreview = document.getElementById('currentImagePreview');
    const currentImg = document.getElementById('currentImg');
    
    if (articulo.imagen && articulo.imagen.trim() !== '') {
        currentImg.src = articulo.imagen;
        currentImagePreview.style.display = 'block';
        console.log('✅ Imagen actual mostrada:', articulo.imagen);
    } else {
        currentImagePreview.style.display = 'none';
        console.log('ℹ️ No hay imagen actual para mostrar');
    }
    
    // Limpiar preview de nueva imagen
    document.getElementById('editImagePreview').style.display = 'none';
    document.getElementById('edit_imagen').value = '';
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalEditarArticulo'));
    modal.show();
    
    console.log('🎯 Modal de edición abierto correctamente');
}

// Preview de nueva imagen en edición
document.getElementById('edit_imagen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('editImagePreview');
    const previewImg = document.getElementById('editPreviewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            console.log('✅ Preview de nueva imagen mostrado');
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        console.log('ℹ️ Preview de nueva imagen ocultado');
    }
});

// Limpiar formulario cuando se cierre el modal
document.getElementById('modalEditarArticulo').addEventListener('hidden.bs.modal', function () {
    const form = this.querySelector('form');
    form.reset();
    document.getElementById('editImagePreview').style.display = 'none';
    document.getElementById('currentImagePreview').style.display = 'none';
    console.log('🧹 Modal limpiado al cerrarse');
});
</script>