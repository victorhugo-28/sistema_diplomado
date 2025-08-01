<!-- Modal para Crear Ingreso -->
<div class="modal fade" id="modalCrearIngreso" tabindex="-1" aria-labelledby="modalCrearIngreso" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('ingresos.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearIngresoLabel">
                        <i class="fas fa-money-bill-wave me-2"></i>Crear Nuevo Ingreso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ingresotipo_comprobante" class="form-label">Tipo Comprobante</label>
                            <select class="form-select" id="ingresotipo_comprobante" name="ingresotipo_comprobante" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">Boleta</option>
                                <option value="2">Factura</option>
                                <option value="3">Nota de Crédito</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ingresoserie_comprobante" class="form-label">Serie Comprobante</label>
                            <input type="number" class="form-control" id="ingresoserie_comprobante" name="ingresoserie_comprobante" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ingresonumero_comprobante" class="form-label">Número Comprobante</label>
                            <input type="number" class="form-control" id="ingresonumero_comprobante" name="ingresonumero_comprobante" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ingresofecha_hora" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="ingresofecha_hora" name="ingresofecha_hora" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ingresoimpuesto" class="form-label">Impuesto (%)</label>
                            <input type="number" class="form-control" id="ingresoimpuesto" name="ingresoimpuesto" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ingresototal_compra" class="form-label">Total Compra</label>
                            <input type="number" class="form-control" id="ingresototal_compra" name="ingresototal_compra" step="0.01" value="0.00" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="idproveedor" class="form-label">Proveedor</label>
                            <select class="form-select" id="idproveedor" name="idproveedor" required>
                                <option value="">Seleccionar proveedor...</option>
                                @if(isset($proveedores) && count($proveedores) > 0)
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor['id'] }}">{{ $proveedor['nombre'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ingresocondicion" class="form-label">Condición</label>
                            <select class="form-select" id="ingresocondicion" name="ingresocondicion">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save me-2"></i>Crear Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>