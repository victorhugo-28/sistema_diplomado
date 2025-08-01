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
                        <div class="col-md-6 mb-3">
                            <label for="articulonombre" class="form-label">Nombre del Artículo</label>
                            <input type="text" class="form-control" id="articulonombre" name="articulonombre" maxlength="100" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="articulostock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="articulostock" name="articulostock" value="0" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="articulodescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="articulodescripcion" name="articulodescripcion" rows="3" maxlength="500"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="articuloimagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="articuloimagen" name="articuloimagen" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="articulocodigogener" class="form-label">Código General</label>
                            <input type="text" class="form-control" id="articulocodigogener" name="articulocodigogener" maxlength="30">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="articulocodigo" class="form-label">Código Artículo</label>
                            <input type="text" class="form-control" id="articulocodigo" name="articulocodigo" maxlength="255">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="articulofecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="articulofecha" name="articulofecha" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="articulohora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="articulohora" name="articulohora" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_tipo" class="form-label">Tipo de Artículo</label>
                            <select class="form-select" id="id_tipo" name="id_tipo" required>
                                <option value="">Seleccionar tipo...</option>
                                @if(isset($tipos) && count($tipos) > 0)
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo['id'] }}">{{ $tipo['nombre'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_proveedor" class="form-label">Proveedor</label>
                            <select class="form-select" id="id_proveedor" name="id_proveedor" required>
                                <option value="">Seleccionar proveedor...</option>
                                @if(isset($proveedores) && count($proveedores) > 0)
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor['id'] }}">{{ $proveedor['nombre'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Crear Artículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>