<!-- Modal para Crear Ingreso -->
<div class="modal fade" id="modalCrearIngreso" tabindex="-1" aria-labelledby="modalCrearIngresoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" action="{{ route('ingresos.store') }}" id="formCrearIngreso">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearIngresoLabel">
                        <i class="fas fa-money-bill-wave me-2"></i>Crear Nuevo Ingreso
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
                                    <label for="ingresotipo_comprobante" class="form-label">Tipo Comprobante <span class="text-danger">*</span></label>
                                    <select class="form-select" id="ingresotipo_comprobante" name="ingresotipo_comprobante" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="1">Boleta</option>
                                        <option value="2">Factura</option>
                                        <option value="3">Nota de Crédito</option>
                                        <option value="4">Recibo</option>
                                        <option value="5">Guía de Remisión</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="idproveedor" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                    <select class="form-select" id="idproveedor" name="idproveedor" required>
                                        <option value="">Seleccionar proveedor...</option>
                                        @if(isset($proveedores) && is_array($proveedores) && count($proveedores) > 0)
                                            @foreach($proveedores as $proveedor)
                                                @if(is_array($proveedor) && isset($proveedor['id']) && isset($proveedor['nombre']))
                                                    <option value="{{ $proveedor['id'] }}">
                                                        {{ $proveedor['nombre'] }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Campo hidden para enviar el total calculado -->
                            <input type="hidden" id="ingresototal_compra" name="ingresototal_compra" value="0">
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-info">
                                        <strong><i class="fas fa-info-circle me-2"></i>Información Automática:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li><strong>Serie y Número:</strong> Se generan automáticamente (COM-000001, 000001, etc.)</li>
                                            <li><strong>Fecha/Hora:</strong> Se asigna la actual del sistema al momento de crear</li>
                                            <li><strong>Impuesto:</strong> Fijo al 13% sobre el subtotal</li>
                                            <li><strong>Total:</strong> Se calcula automáticamente según los productos agregados</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de Productos -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-box me-2"></i>Productos de la Compra</h6>
                            <button type="button" class="btn btn-success btn-sm" id="btnAgregarProducto">
                                <i class="fas fa-plus me-1"></i>Agregar Producto
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="productosContainer">
                                <!-- Aquí se agregarán dinámicamente los productos -->
                            </div>

                            <!-- Template oculto para productos -->
                            <div id="productoTemplate" style="display: none;">
                                <div class="producto-item border rounded p-3 mb-3 position-relative">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-eliminar-producto">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Producto <span class="text-danger">*</span></label>
                                            <select class="form-select select-articulo" name="detalles[INDEX][idarticulo]" required>
                                                <option value="">Seleccionar producto...</option>
                                                @if(isset($articulos) && is_array($articulos) && count($articulos) > 0)
                                                    @foreach($articulos as $articulo)
                                                        @if(is_array($articulo) && isset($articulo['id']) && isset($articulo['nombre']))
                                                            <option value="{{ $articulo['id'] }}" 
                                                                    data-precio="{{ $articulo['precio'] ?? 0 }}"
                                                                    data-codigo="{{ $articulo['codigo'] ?? '' }}"
                                                                    data-descripcion="{{ $articulo['descripcion'] ?? '' }}">
                                                                {{ $articulo['codigo'] ?? 'S/C' }} - {{ $articulo['nombre'] }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control input-cantidad" name="detalles[INDEX][cantidad]" 
                                                   min="1" step="1" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Precio Compra <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control input-precio" name="detalles[INDEX][precio]" 
                                                   min="0.01" step="0.01" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Precio Venta</label>
                                            <input type="number" class="form-control input-precio-venta" name="detalles[INDEX][precio_venta]" 
                                                   min="0.01" step="0.01">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Subtotal</label>
                                            <input type="text" class="form-control input-subtotal" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Código</label>
                                            <input type="text" class="form-control input-codigo" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Descripción</label>
                                            <textarea class="form-control input-descripcion" rows="2" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen de totales -->
                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span id="subtotalCompra">$0.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Impuesto (13%):</span>
                                                <span id="impuestoCompra">$0.00</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between fw-bold">
                                                <span>Total:</span>
                                                <span id="totalCompra">$0.00</span>
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
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <i class="fas fa-save me-1"></i>Guardar Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  console.log('✅ Inicializando modal...');

  const IMPUESTO_FIJO = 13;
  let contadorProductos = 0;

  const btnAgregar = document.getElementById('btnAgregarProducto');
  const formulario = document.getElementById('formCrearIngreso');
  const container = document.getElementById('productosContainer');
  const template = document.getElementById('productoTemplate');

  if (!btnAgregar || !formulario || !container || !template) {
    console.error('❌ ERROR: Elementos no encontrados');
    return;
  }

  // Agrega un nuevo producto
  function agregarProducto() {
    console.log('➕ Agregando producto #' + contadorProductos);

    const nuevoProducto = template.cloneNode(true);
    nuevoProducto.id = `producto_${contadorProductos}`;
    nuevoProducto.style.display = 'block';

    nuevoProducto.innerHTML = nuevoProducto.innerHTML.replace(/INDEX/g, contadorProductos);

    container.appendChild(nuevoProducto);
    agregarEventosProducto(nuevoProducto);

    contadorProductos++;
    console.log('✅ Producto agregado');
  }

  // Agrega eventos a los inputs y selects de un producto
  function agregarEventosProducto(productoEl) {
    const selectArticulo = productoEl.querySelector('.select-articulo');
    const inputCantidad = productoEl.querySelector('.input-cantidad');
    const inputPrecio = productoEl.querySelector('.input-precio');
    const inputPrecioVenta = productoEl.querySelector('.input-precio-venta');
    const btnEliminar = productoEl.querySelector('.btn-eliminar-producto');

    if (selectArticulo) {
      selectArticulo.addEventListener('change', () => {
        const selectedOption = selectArticulo.options[selectArticulo.selectedIndex];
        if (selectedOption.value) {
          const precio = parseFloat(selectedOption.getAttribute('data-precio')) || 0;
          const codigo = selectedOption.getAttribute('data-codigo') || '';
          const descripcion = selectedOption.getAttribute('data-descripcion') || '';

          if (inputPrecio) inputPrecio.value = precio.toFixed(2);
          if (inputPrecioVenta) inputPrecioVenta.value = (precio * 1.3).toFixed(2);

          const inputCodigo = productoEl.querySelector('.input-codigo');
          const inputDescripcion = productoEl.querySelector('.input-descripcion');

          if (inputCodigo) inputCodigo.value = codigo;
          if (inputDescripcion) inputDescripcion.value = descripcion;

          calcularSubtotal(productoEl);
        } else {
          limpiarProducto(productoEl);
        }
        calcularTotales();
      });
    }

    // Recalcular subtotal al cambiar cantidad o precio
    [inputCantidad, inputPrecio].forEach(input => {
      if (input) {
        input.addEventListener('input', () => {
          calcularSubtotal(productoEl);
          calcularTotales();
        });
      }
    });

    // Eliminar producto
    if (btnEliminar) {
      btnEliminar.addEventListener('click', () => {
        productoEl.remove();
        calcularTotales();
      });
    }
  }

  // Limpia los campos de un producto
  function limpiarProducto(productoEl) {
    ['.input-precio', '.input-precio-venta', '.input-codigo', '.input-descripcion', '.input-subtotal'].forEach(selector => {
      const el = productoEl.querySelector(selector);
      if (el) el.value = '';
    });
  }

  // Calcula el subtotal de un producto
  function calcularSubtotal(productoEl) {
    const cantidad = parseFloat(productoEl.querySelector('.input-cantidad')?.value) || 0;
    const precio = parseFloat(productoEl.querySelector('.input-precio')?.value) || 0;
    const subtotal = cantidad * precio;

    const inputSubtotal = productoEl.querySelector('.input-subtotal');
    if (inputSubtotal) inputSubtotal.value = `$${subtotal.toFixed(2)}`;
  }

  // Calcula totales generales y actualiza el UI
  function calcularTotales() {
    let subtotalGeneral = 0;
    const productos = container.querySelectorAll('.producto-item');

    productos.forEach(producto => {
      const cantidad = parseFloat(producto.querySelector('.input-cantidad')?.value) || 0;
      const precio = parseFloat(producto.querySelector('.input-precio')?.value) || 0;
      subtotalGeneral += cantidad * precio;
    });

    const impuestoMonto = subtotalGeneral * (IMPUESTO_FIJO / 100);
    const totalGeneral = subtotalGeneral + impuestoMonto;

    console.log(`💰 Subtotal: $${subtotalGeneral.toFixed(2)} + Impuesto 13%: $${impuestoMonto.toFixed(2)} = Total: $${totalGeneral.toFixed(2)}`);

    const subtotalElement = document.getElementById('subtotalCompra');
    const impuestoElement = document.getElementById('impuestoCompra');
    const totalElement = document.getElementById('totalCompra');
    const totalInput = formulario.querySelector('input[name="ingresototal_compra"]');

    if (subtotalElement) subtotalElement.textContent = `$${subtotalGeneral.toFixed(2)}`;
    if (impuestoElement) impuestoElement.textContent = `$${impuestoMonto.toFixed(2)}`;
    if (totalElement) totalElement.textContent = `$${totalGeneral.toFixed(2)}`;
    if (totalInput) totalInput.value = totalGeneral.toFixed(2);
  }

  btnAgregar.addEventListener('click', e => {
    e.preventDefault();
    agregarProducto();
  });

  const btnGuardar = document.getElementById('btnGuardar');
  if (btnGuardar) {
    btnGuardar.addEventListener('click', e => {
      e.preventDefault();

      calcularTotales();

      setTimeout(() => {
        const totalInput = formulario.querySelector('input[name="ingresototal_compra"]');
        if (!totalInput) {
          console.error('❌ ERROR: No se encontró el input ingresototal_compra dentro del formulario');
          return;
        }

        console.log('💾 Total a enviar (ingresototal_compra):', totalInput.value);

        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
        btnGuardar.disabled = true;

        formulario.submit();
      }, 50);
    });
  }

  // Producto inicial
  agregarProducto();

  console.log('✅ Modal listo');
});

</script>