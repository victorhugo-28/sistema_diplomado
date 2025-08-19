<!-- Modal para Editar Ingreso -->
<div class="modal fade" id="modalEditarIngreso" tabindex="-1" aria-labelledby="modalEditarIngresoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formEditarIngreso" action="{{ route('ingresos.editar', ['id' => '__id__']) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarIngresoLabel">
                        <i class="fas fa-edit me-2"></i>Editar Ingreso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <!-- Información General -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Tipo Comprobante -->
                                <div class="col-md-6 mb-3">
                                    <label for="editar_tipo_comprobante" class="form-label">Tipo Comprobante <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editar_tipo_comprobante" name="ingresotipo_comprobante" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de Crédito">Nota de Crédito</option>
                                        <option value="Recibo">Recibo</option>
                                        <option value="Guía de Remisión">Guía de Remisión</option>
                                    </select>
                                </div>

                                <!-- Proveedor -->
                                <div class="col-md-6 mb-3">
                                    <label for="editar_proveedor" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editar_proveedor" name="idproveedor" required>
                                        <option value="">Seleccionar proveedor...</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor['id'] }}">{{ $proveedor['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Serie -->
                                <div class="col-md-6 mb-3">
                                    <label for="editar_serie" class="form-label">Serie Comprobante</label>
                                    <input type="text" class="form-control" id="editar_serie" name="ingresoserie_comprobante" required>
                                </div>

                                <!-- Número -->
                                <div class="col-md-6 mb-3">
                                    <label for="editar_numero" class="form-label">Número Comprobante</label>
                                    <input type="text" class="form-control" id="editar_numero" name="ingresonumero_comprobante" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Fecha/Hora -->
                                <div class="col-md-6 mb-3">
                                    <label for="editar_fecha_hora" class="form-label">Fecha y Hora</label>
                                    <input type="datetime-local" class="form-control" id="editar_fecha_hora" name="ingresofecha_hora" required>
                                </div>

                                <!-- Impuesto -->
                                <div class="col-md-3 mb-3">
                                    <label for="editar_impuesto" class="form-label">Impuesto (%)</label>
                                    <input type="number" class="form-control" id="editar_impuesto" name="ingresoimpuesto" min="0" max="100" required>
                                </div>

                                <!-- Total -->
                                <div class="col-md-3 mb-3">
                                    <label for="editar_total" class="form-label">Total Compra</label>
                                    <input type="number" class="form-control" id="editar_total" name="ingresototal_compra" step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Condición -->
                                <div class="col-md-6 mb-3">
                                    <label for="editar_condicion" class="form-label">Condición</label>
                                    <select class="form-select" id="editar_condicion" name="ingresocondicion" required>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnActualizarIngreso">
                        <i class="fas fa-save me-1"></i>Actualizar Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>