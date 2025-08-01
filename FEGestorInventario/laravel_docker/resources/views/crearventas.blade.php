<!-- Modal para Crear Venta -->
<div class="modal fade" id="modalCrearVenta" tabindex="-1" aria-labelledby="modalCrearVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('ventas.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearVentaLabel">
                        <i class="fas fa-shopping-cart me-2"></i>Crear Nueva Venta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="ventatipo_comprobante" class="form-label">Tipo Comprobante</label>
                            <select class="form-select" id="ventatipo_comprobante" name="ventatipo_comprobante" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">Boleta</option>
                                <option value="2">Factura</option>
                                <option value="3">Nota de Venta</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ventaserie_comprobante" class="form-label">Serie</label>
                            <input type="number" class="form-control" id="ventaserie_comprobante" name="ventaserie_comprobante" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ventanum_comprobante" class="form-label">Número</label>
                            <input type="number" class="form-control" id="ventanum_comprobante" name="ventanum_comprobante" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ventafecha_hora" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="ventafecha_hora" name="ventafecha_hora" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ventaimpuesto" class="form-label">Impuesto (%)</label>
                            <input type="number" class="form-control" id="ventaimpuesto" name="ventaimpuesto" value="0" min="0" max="100">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ventatotal_venta" class="form-label">Total Venta</label>
                            <input type="number" class="form-control" id="ventatotal_venta" name="ventatotal_venta" step="0.01" value="0.00" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="idcliente" class="form-label">Cliente</label>
                            <select class="form-select" id="idcliente" name="idcliente" required>
                                <option value="">Seleccionar cliente...</option>
                                @if(isset($clientes) && count($clientes) > 0)
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente['idcliente'] }}">{{ $cliente['nombre'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="ventacondicion" class="form-label">Condición</label>
                            <select class="form-select" id="ventacondicion" name="ventacondicion">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ventapago_cliente" class="form-label">Pago Cliente</label>
                            <input type="number" class="form-control" id="ventapago_cliente" name="ventapago_cliente" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ventacambio" class="form-label">Cambio</label>
                            <input type="number" class="form-control" id="ventacambio" name="ventacambio" step="0.01" min="0" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-save me-2"></i>Crear Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>