<!-- Modal para Crear Venta -->
<div class="modal fade" id="modalCrearVenta" tabindex="-1" aria-labelledby="modalCrearVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" action="{{ route('ventas.store') }}" id="formCrearVenta">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearVentaLabel">
                        <i class="fas fa-cash-register me-2"></i>Crear Nueva Venta
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
                                <div class="col-md-6 mb-3">
                                    <label for="ventatipo_comprobante" class="form-label">Tipo Comprobante <span class="text-danger">*</span></label>
                                    <select class="form-select" id="ventatipo_comprobante" name="ventatipo_comprobante" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="1">Boleta</option>
                                        <option value="2">Factura</option>
                                        <option value="3">Nota de Crédito</option>
                                        <option value="4">Recibo</option>
                                        <option value="5">Guía de Remisión</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="idcliente" class="form-label">Cliente <span class="text-danger">*</span></label>
                                    <select class="form-select" id="idcliente" name="idcliente" required>
                                        <option value="">Seleccionar cliente...</option>
                                        @if(isset($clientes) && count($clientes) > 0)
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id ?? $cliente['id'] }}">
                                                    {{ $cliente->nombre ?? $cliente['nombre'] }} - {{ $cliente->email ?? $cliente['email'] ?? 'Sin email' }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Campos para pago y cambio -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="pago_cliente" class="form-label">Pago Cliente <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="pago_cliente" name="ventapago_cliente" 
                                           min="0.01" step="0.01" required placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cambio_display" class="form-label">Cambio</label>
                                    <div class="form-control bg-light d-flex align-items-center justify-content-center">
                                        <span class="badge badge-custom badge-total" id="cambio_display">$0.00</span>
                                    </div>
                                    <input type="hidden" id="ventacambio" name="ventacambio" value="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="total_venta" class="form-label">Total Venta <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="total_venta" name="ventatotal_venta" 
                                           min="0.01" step="0.01" readonly style="background-color: #f8f9fa;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detalles de Productos -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-box me-2"></i>Productos de la Venta</h6>
                            <button type="button" class="btn btn-success btn-sm" id="btnAgregarProductoVenta">
                                <i class="fas fa-plus me-1"></i>Agregar Producto
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="productosVentaContainer">
                                <!-- Aquí se agregarán dinámicamente los productos -->
                            </div>
                            
                            <!-- Descuento Global -->
                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card bg-info bg-opacity-10 border-info">
                                        <div class="card-body">
                                            <label for="descuento_general" class="form-label fw-bold">
                                                <i class="fas fa-percentage me-1"></i>Descuento General (%)
                                            </label>
                                            <input type="number" class="form-control" id="descuento_general" 
                                                   name="detalle_ventadescuento" min="0" max="100" step="0.01" value="0"
                                                   placeholder="0.00">
                                            <small class="text-muted">Descuento aplicado al subtotal</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Resumen de totales -->
                            <div class="row mt-3">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span id="subtotalVenta">$0.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Descuento (<span id="porcentajeDescuento">0</span>%):</span>
                                                <span id="descuentosVenta" class="text-danger">-$0.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal con descuento:</span>
                                                <span id="subtotalConDescuento">$0.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Impuesto (13%):</span>
                                                <span id="impuestoVenta">$0.00</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between fw-bold">
                                                <span>Total:</span>
                                                <span id="totalVenta">$0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarVenta">
                        <i class="fas fa-save me-1"></i>Guardar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 === INICIANDO DEBUG MODAL VENTAS ===');
    
    const IMPUESTO_FIJO = 13;
    let contadorProductosVenta = 0;
    const btnAgregar = document.getElementById('btnAgregarProductoVenta');
    const formulario = document.getElementById('formCrearVenta');
    const container = document.getElementById('productosVentaContainer');
    const descuentoGeneralInput = document.getElementById('descuento_general');
    
    console.log('🔍 Elementos encontrados:', {
        btnAgregar: !!btnAgregar,
        formulario: !!formulario,
        container: !!container,
        descuentoGeneral: !!descuentoGeneralInput
    });

    // Obtener datos de Laravel de forma segura
    let articulos = [];
    try {
        @if(isset($articulos))
            articulos = @json($articulos);
            console.log('📦 ARTÍCULOS RECIBIDOS:', articulos);
            console.log('📦 Total artículos:', articulos.length);
            if (articulos.length > 0) {
                console.log('📦 Ejemplo primer artículo:', articulos[0]);
            }
        @else
            console.log('❌ No hay variable $articulos en Laravel');
        @endif
    } catch(e) {
        console.error('❌ ERROR al procesar artículos:', e);
        articulos = [];
    }

    function crearProductoHTML(indice) {
        console.log(`➕ Creando producto HTML #${indice}`);
        
        let optionsHTML = '<option value="">Seleccionar producto...</option>';
        
        if (Array.isArray(articulos) && articulos.length > 0) {
            console.log(`📝 Procesando ${articulos.length} artículos para el select`);
            articulos.forEach((articulo, index) => {
                const id = articulo.id || articulo['id'];
                const nombre = articulo.nombre || articulo['nombre'] || 'Sin nombre';
                const precio = articulo.precio || articulo['precio'] || 0;
                const codigo = articulo.codigo || articulo['codigo'] || '';
                const stock = articulo.stock || articulo['stock'] || 0;
                const descripcion = articulo.descripcion || articulo['descripcion'] || '';
                
                console.log(`  📋 Artículo ${index + 1}:`, {id, nombre, precio, codigo, stock});
                
                optionsHTML += `<option value="${id}" 
                    data-precio="${precio}"
                    data-codigo="${codigo}"
                    data-stock="${stock}"
                    data-descripcion="${descripcion}">
                    ${codigo || 'S/C'} - ${nombre} (Stock: ${stock})
                </option>`;
            });
        } else {
            console.log('⚠️ No hay artículos para mostrar en el select');
        }

        const html = `
            <div class="producto-venta-item border rounded p-3 mb-3 position-relative" data-index="${indice}">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-eliminar-producto-venta">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Producto <span class="text-danger">*</span></label>
                        <select class="form-select select-articulo-venta" name="detalles[${indice}][idarticulo]" required>
                            ${optionsHTML}
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" class="form-control input-cantidad-venta" 
                            name="detalles[${indice}][detalle_ventacantidad]" 
                            min="1" step="1" required>
                        <small class="text-muted">Stock: <span class="stock-disponible">0</span></small>
                        <div class="invalid-feedback text-danger d-none small mensaje-error-stock">
                            La cantidad supera el stock disponible.
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Precio Venta <span class="text-danger">*</span></label>
                        <input type="number" class="form-control input-precio-venta" name="detalles[${indice}][detalle_ventaprecio_venta]" 
                               min="0.01" step="0.01" required >
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control input-subtotal-venta" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control input-codigo-venta" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control input-descripcion-venta" rows="2" readonly></textarea>
                    </div>
                </div>
            </div>
        `;
        
        console.log(`✅ HTML producto #${indice} creado con nombre: detalles[${indice}][*]`);
        return html;
    }

    function agregarProductoVenta() {
        console.log(`🔧 === AGREGANDO PRODUCTO #${contadorProductosVenta} ===`);
        
        const nuevoProductoHTML = crearProductoHTML(contadorProductosVenta);
        container.insertAdjacentHTML('beforeend', nuevoProductoHTML);
        
        const nuevoProducto = container.lastElementChild;
        console.log('🎯 Producto agregado al DOM:', nuevoProducto);
        
        agregarEventosProductoVenta(nuevoProducto);
        contadorProductosVenta++;
        
        console.log(`✅ Producto #${contadorProductosVenta - 1} listo`);
    }

    function agregarEventosProductoVenta(productoEl) {
        const selectArticulo = productoEl.querySelector('.select-articulo-venta');
        const inputCantidad = productoEl.querySelector('.input-cantidad-venta');
        const inputPrecio = productoEl.querySelector('.input-precio-venta');
        const btnEliminar = productoEl.querySelector('.btn-eliminar-producto-venta');
        const stockDisplay = productoEl.querySelector('.stock-disponible');

        console.log('🎛️ Agregando eventos a producto:', {
            selectArticulo: !!selectArticulo,
            inputCantidad: !!inputCantidad,
            inputPrecio: !!inputPrecio,
            btnEliminar: !!btnEliminar
        });

        if (selectArticulo) {
            selectArticulo.addEventListener('change', () => {
                const selectedOption = selectArticulo.options[selectArticulo.selectedIndex];
                console.log('🔄 Producto seleccionado:', {
                    value: selectedOption.value,
                    text: selectedOption.text,
                    precio: selectedOption.getAttribute('data-precio'),
                    stock: selectedOption.getAttribute('data-stock')
                });
                
                if (selectedOption.value) {
                    const precio = parseFloat(selectedOption.getAttribute('data-precio')) || 0;
                    const codigo = selectedOption.getAttribute('data-codigo') || '';
                    const stock = parseFloat(selectedOption.getAttribute('data-stock')) || 0;
                    const descripcion = selectedOption.getAttribute('data-descripcion') || '';

                    console.log('💰 Datos del artículo:', {precio, codigo, stock, descripcion});

                    if (inputPrecio) inputPrecio.value = precio.toFixed(2);
                    if (stockDisplay) stockDisplay.textContent = stock;

                    const inputCodigo = productoEl.querySelector('.input-codigo-venta');
                    const inputDescripcion = productoEl.querySelector('.input-descripcion-venta');
                    if (inputCodigo) inputCodigo.value = codigo;
                    if (inputDescripcion) inputDescripcion.value = descripcion;

                    if (inputCantidad) {
                        inputCantidad.setAttribute('max', stock);
                        inputCantidad.title = `Stock disponible: ${stock}`;
                    }

                    calcularSubtotalVenta(productoEl);
                } else {
                    console.log('🧹 Limpiando producto (opción vacía seleccionada)');
                    limpiarProductoVenta(productoEl);
                }
                calcularTotalesVenta();
            });
        }

        [inputCantidad, inputPrecio].forEach((input, index) => {
            if (input) {
                input.addEventListener('input', () => {
                    console.log(`📊 Input cambió [${['cantidad', 'precio'][index]}]:`, input.value);
                    
                    if (input === inputCantidad) {
                        const stockMax = parseFloat(input.getAttribute('max')) || 0;
                        const cantidadIngresada = parseFloat(input.value) || 0;
                        
                        const mensajeError = productoEl.querySelector('.mensaje-error-stock');
                        if (cantidadIngresada > stockMax) {
                            console.log('⚠️ Stock insuficiente:', {cantidadIngresada, stockMax});
                            input.classList.add('is-invalid');
                            if (mensajeError) mensajeError.classList.remove('d-none');
                        } else {
                            input.classList.remove('is-invalid');
                            if (mensajeError) mensajeError.classList.add('d-none');
                        }
                    }
                    
                    calcularSubtotalVenta(productoEl);
                    calcularTotalesVenta();
                });
            }
        });

        if (btnEliminar) {
            btnEliminar.addEventListener('click', () => {
                console.log('🗑️ Eliminando producto');
                productoEl.remove();
                calcularTotalesVenta();
            });
        }
    }

    function limpiarProductoVenta(productoEl) {
        console.log('🧹 Limpiando campos del producto');
        ['.input-precio-venta', '.input-codigo-venta', '.input-descripcion-venta', '.input-subtotal-venta'].forEach(selector => {
            const el = productoEl.querySelector(selector);
            if (el) el.value = '';
        });
        
        const stockDisplay = productoEl.querySelector('.stock-disponible');
        if (stockDisplay) stockDisplay.textContent = '0';
    }

    function calcularSubtotalVenta(productoEl) {
        const cantidad = parseFloat(productoEl.querySelector('.input-cantidad-venta')?.value) || 0;
        const precio = parseFloat(productoEl.querySelector('.input-precio-venta')?.value) || 0;
        const subtotal = cantidad * precio;

        console.log('🧮 Calculando subtotal producto:', {cantidad, precio, subtotal});

        const inputSubtotal = productoEl.querySelector('.input-subtotal-venta');
        if (inputSubtotal) inputSubtotal.value = `$${subtotal.toFixed(2)}`;
    }

    function calcularCambioCrear() {
        const pago = parseFloat(document.getElementById('pago_cliente')?.value) || 0;
        const total = parseFloat(document.getElementById('total_venta')?.value) || 0;
        const cambio = Math.max(0, pago - total);

        console.log('💳 Calculando cambio:', {pago, total, cambio});

        const cambioDisplay = document.getElementById('cambio_display');
        const cambioInput = document.getElementById('ventacambio');

        if (cambioDisplay) {
            cambioDisplay.textContent = `$${cambio.toFixed(2)}`;
        }
        if (cambioInput) {
            cambioInput.value = cambio.toFixed(2);
        }
    }
    
    function calcularTotalesVenta() {
        console.log('🧮 === CALCULANDO TOTALES VENTA ===');
        
        let subtotalGeneral = 0;
        const productos = container.querySelectorAll('.producto-venta-item');
        console.log(`📋 Productos encontrados: ${productos.length}`);

        // Calcular subtotal de todos los productos
        productos.forEach((producto, index) => {
            const cantidad = parseFloat(producto.querySelector('.input-cantidad-venta')?.value) || 0;
            const precio = parseFloat(producto.querySelector('.input-precio-venta')?.value) || 0;
            
            console.log(`  Producto ${index + 1}:`, {cantidad, precio, subtotal: cantidad * precio});
            
            subtotalGeneral += cantidad * precio;
        });

        // Obtener porcentaje de descuento general
        const porcentajeDescuento = parseFloat(descuentoGeneralInput?.value) || 0;
        
        // Calcular descuento en monto
        const montoDescuento = subtotalGeneral * (porcentajeDescuento / 100);
        
        // Calcular subtotal con descuento
        const subtotalConDescuento = subtotalGeneral - montoDescuento;
        
        // Calcular impuesto sobre el subtotal con descuento
        const impuestoMonto = subtotalConDescuento * (IMPUESTO_FIJO / 100);
        
        // Calcular total final
        const totalGeneral = subtotalConDescuento + impuestoMonto;

        console.log('💰 RESUMEN TOTALES:', {
            subtotalGeneral: subtotalGeneral.toFixed(2),
            porcentajeDescuento: porcentajeDescuento,
            montoDescuento: montoDescuento.toFixed(2),
            subtotalConDescuento: subtotalConDescuento.toFixed(2),
            impuestoMonto: impuestoMonto.toFixed(2),
            totalGeneral: totalGeneral.toFixed(2)
        });

        // Actualizar elementos del DOM
        const subtotalElement = document.getElementById('subtotalVenta');
        const porcentajeElement = document.getElementById('porcentajeDescuento');
        const descuentosElement = document.getElementById('descuentosVenta');
        const subtotalConDescuentoElement = document.getElementById('subtotalConDescuento');
        const impuestoElement = document.getElementById('impuestoVenta');
        const totalElement = document.getElementById('totalVenta');
        const totalInput = document.getElementById('total_venta');

        if (subtotalElement) subtotalElement.textContent = `$${subtotalGeneral.toFixed(2)}`;
        if (porcentajeElement) porcentajeElement.textContent = porcentajeDescuento.toFixed(1);
        if (descuentosElement) descuentosElement.textContent = `-$${montoDescuento.toFixed(2)}`;
        if (subtotalConDescuentoElement) subtotalConDescuentoElement.textContent = `$${subtotalConDescuento.toFixed(2)}`;
        if (impuestoElement) impuestoElement.textContent = `$${impuestoMonto.toFixed(2)}`;
        if (totalElement) totalElement.textContent = `$${totalGeneral.toFixed(2)}`;
        if (totalInput) totalInput.value = totalGeneral.toFixed(2);

        calcularCambioCrear();
    }

    // Event listener para el descuento general
    if (descuentoGeneralInput) {
        descuentoGeneralInput.addEventListener('input', () => {
            const valor = parseFloat(descuentoGeneralInput.value) || 0;
            
            // Validar que el porcentaje esté entre 0 y 100
            if (valor < 0) {
                descuentoGeneralInput.value = 0;
            } else if (valor > 100) {
                descuentoGeneralInput.value = 100;
            }
            
            console.log('📊 Descuento general cambió:', descuentoGeneralInput.value);
            calcularTotalesVenta();
        });
    }

    if (btnAgregar) {
        btnAgregar.addEventListener('click', e => {
            e.preventDefault();
            console.log('🆕 Botón agregar producto clickeado');
            agregarProductoVenta();
        });
    }

    const btnGuardar = document.getElementById('btnGuardarVenta');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', e => {
            e.preventDefault();
            console.log('💾 === INICIANDO GUARDADO VENTA ===');

            const productos = container.querySelectorAll('.producto-venta-item');
            console.log(`🔍 Productos a validar: ${productos.length}`);
            
            if (productos.length === 0) {
                console.log('❌ No hay productos para guardar');
                alert('Debe agregar al menos un producto a la venta.');
                return;
            }

            // Validar stock
            let stockValido = true;
            productos.forEach((producto, index) => {
                const inputCantidad = producto.querySelector('.input-cantidad-venta');
                if (inputCantidad && inputCantidad.classList.contains('is-invalid')) {
                    console.log(`❌ Producto ${index + 1} tiene stock inválido`);
                    stockValido = false;
                }
            });

            if (!stockValido) {
                console.log('❌ Validación de stock falló');
                alert('Verifique las cantidades. Hay productos con stock insuficiente.');
                return;
            }

            // Validar pago
            const totalVenta = parseFloat(document.getElementById('total_venta').value) || 0;
            const pagoCliente = parseFloat(document.getElementById('pago_cliente').value) || 0;

            console.log('💰 Validando pago:', {totalVenta, pagoCliente});

            if (pagoCliente < totalVenta) {
                console.log('❌ Pago insuficiente');
                alert('El pago del cliente debe ser mayor o igual al total de la venta.');
                return;
            }

            // Recopilar datos del formulario
            console.log('📝 === RECOPILANDO DATOS DEL FORMULARIO ===');
            const formData = new FormData(formulario);
            const datosFormulario = {};
            
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
                datosFormulario[key] = value;
            }
            
            console.log('📦 DATOS FINALES A ENVIAR:', datosFormulario);

            btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
            btnGuardar.disabled = true;

            console.log('🚀 Enviando formulario...');
            setTimeout(() => {
                formulario.submit();
            }, 100);
        });
    }

    const inputPago = document.getElementById('pago_cliente');
    if (inputPago) {
        inputPago.addEventListener('input', () => {
            console.log('💳 Pago cliente cambió:', inputPago.value);
            calcularCambioCrear();
        });
    }

    // Agregar primer producto
    console.log('🎬 Agregando primer producto automáticamente...');
    agregarProductoVenta();

    console.log('✅ === MODAL VENTAS DEBUG LISTO ===');
});
</script>

<style>
.badge-custom {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

.badge-total {
    background-color: #28a745;
    color: white;
}

.bg-info {
    --bs-bg-opacity: 0.1;
}

.border-info {
    border-color: #0dcaf0 !important;
}

.text-danger {
    color: #dc3545 !important;
}
</style>