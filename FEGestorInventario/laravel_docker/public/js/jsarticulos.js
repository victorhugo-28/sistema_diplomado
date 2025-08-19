document.addEventListener('DOMContentLoaded', function () {
    // Auto-ocultar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (typeof bootstrap !== 'undefined') {
                const bootstrapAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bootstrapAlert.close();
            }
        }, 5000);
    });

    // ✅ VALIDACIÓN CORREGIDA - Usar nombres nuevos del controlador
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // ✅ CORREGIDO: Buscar por name="nombre" y name="stock"
            const nombre = form.querySelector('input[name="nombre"]');
            const stock = form.querySelector('input[name="stock"]');

            if (nombre && nombre.value.trim().length < 2) {
                e.preventDefault();
                alert('El nombre debe tener al menos 2 caracteres.');
                return false;
            }

            if (stock && (isNaN(stock.value) || parseInt(stock.value) < 0)) {
                e.preventDefault();
                alert('El stock debe ser un número mayor o igual a 0.');
                return false;
            }
        });
    });

    // Campo de búsqueda
    const pageHeader = document.querySelector('.page-header');
    if (pageHeader && !document.querySelector('#searchArticulos')) {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'mt-3';
        searchContainer.innerHTML = `
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchArticulos" class="form-control border-start-0" 
                       placeholder="Buscar por nombre, código, tipo o proveedor...">
            </div>
        `;
        pageHeader.appendChild(searchContainer);
        
        // Evento de búsqueda
        document.getElementById('searchArticulos').addEventListener('input', function() {
            filtrarArticulos(this.value);
        });
    }

    // Estados de carga para botones
    const buttons = document.querySelectorAll('button[type="submit"]');
    buttons.forEach(function(button) {
        const form = button.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                
                setTimeout(function() {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }, 5000);
            });
        }
    });
});

// Búsqueda en tiempo real
function filtrarArticulos(searchTerm) {
    const lowerSearchTerm = searchTerm.toLowerCase();
    const tableRows = document.querySelectorAll('#tableView tbody tr');
    
    tableRows.forEach(function(row) {
        const nombre = row.cells[1].textContent.toLowerCase();
        const codigo = row.cells[3].textContent.toLowerCase();
        const tipo = row.cells[4].textContent.toLowerCase();
        const proveedor = row.cells[5].textContent.toLowerCase();
        
        const shouldShow = nombre.includes(lowerSearchTerm) ||
                         codigo.includes(lowerSearchTerm) ||
                         tipo.includes(lowerSearchTerm) ||
                         proveedor.includes(lowerSearchTerm);
        
        row.style.display = shouldShow ? '' : 'none';
    });
}

// Ordenamiento mejorado
function ordenarArticulos(campo) {
    const tableBody = document.querySelector('#tableView tbody');
    const rows = Array.from(tableBody.querySelectorAll('tr'));
    
    // Determinar dirección de ordenamiento
    let ascending = true;
    if (campo === 'stock') {
        const lastSort = tableBody.getAttribute('data-last-sort');
        const lastDirection = tableBody.getAttribute('data-last-direction');
        
        if (lastSort === 'stock' && lastDirection === 'desc') {
            ascending = true;
        } else {
            ascending = false;
        }
        
        tableBody.setAttribute('data-last-sort', 'stock');
        tableBody.setAttribute('data-last-direction', ascending ? 'asc' : 'desc');
        updateSortIndicator('stock', ascending);
    } else {
        tableBody.setAttribute('data-last-sort', campo);
        tableBody.setAttribute('data-last-direction', 'asc');
        updateSortIndicator(campo, true);
    }
    
    // Ordenar filas
    rows.sort((a, b) => {
        if (campo === 'id') {
            const aId = parseInt(a.cells[0].textContent.replace('#', ''));
            const bId = parseInt(b.cells[0].textContent.replace('#', ''));
            return ascending ? aId - bId : bId - aId;
        }
        if (campo === 'nombre') {
            const aName = a.cells[1].textContent.trim().toLowerCase();
            const bName = b.cells[1].textContent.trim().toLowerCase();
            return ascending ? aName.localeCompare(bName) : bName.localeCompare(aName);
        }
        if (campo === 'stock') {
            const aStock = parseInt(a.getAttribute('data-stock'));
            const bStock = parseInt(b.getAttribute('data-stock'));
            return ascending ? aStock - bStock : bStock - aStock;
        }
    });
    
    // Aplicar ordenamiento
    tableBody.innerHTML = '';
    rows.forEach(row => tableBody.appendChild(row));
    
    // Mostrar mensaje
    showSortMessage(campo, ascending);
}

// Actualizar indicadores visuales
function updateSortIndicator(campo, ascending) {
    document.querySelectorAll('th i.fa-sort, th i.fa-sort-up, th i.fa-sort-down').forEach(icon => {
        icon.className = 'fas fa-sort ms-1';
        icon.style.opacity = '0.5';
    });
    
    const headers = { 'id': 0, 'nombre': 1, 'stock': 2 };
    if (headers[campo] !== undefined) {
        const headerCell = document.querySelectorAll('#tableView th')[headers[campo]];
        const sortIcon = headerCell.querySelector('i.fa-sort');
        if (sortIcon) {
            sortIcon.className = ascending ? 'fas fa-sort-up ms-1' : 'fas fa-sort-down ms-1';
            sortIcon.style.opacity = '1';
            sortIcon.style.color = 'var(--color-tertiary)';
        }
    }
}

// Mostrar mensaje de ordenamiento
function showSortMessage(campo, ascending) {
    let mensaje = '';
    if (campo === 'stock') {
        mensaje = ascending ? 'Ordenado: Stock de BAJO a ALTO' : 'Ordenado: Stock de ALTO a BAJO';
    } else if (campo === 'nombre') {
        mensaje = 'Ordenado: Nombre A-Z';
    } else if (campo === 'id') {
        mensaje = 'Ordenado: ID 1-9';
    }
    
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-primary border-0';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-sort me-2"></i>${mensaje}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

// Crear contenedor de toasts
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1055';
    document.body.appendChild(container);
    return container;
}

// Modal de detalle
function verDetalleArticulo(articulo) {

    

    const modal = new bootstrap.Modal(document.getElementById('modalDetalleArticulo'));
    const contenido = document.getElementById('contenidoDetalleArticulo');
    
    let detallesHTML = `
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-uppercase fw-bold mb-3" style="color: var(--color-primary);">
                    <i class="fas fa-info-circle me-2"></i>Información del Artículo
                </h6>
            </div>
        </div>
    `;
    
    // Agregar imagen si existe
    if (articulo.imagen && articulo.imagen !== '') {
        detallesHTML += `
            <div class="text-center mb-4">
                <img
                    src="${articulo.imagen}"
                    class="img-fluid rounded"
                    style="max-height: 300px; max-width: 100%; object-fit: contain; border: 3px solid blue;"
                    alt="Imagen del artículo"
                    referrerpolicy="no-referrer"
                    onload="console.log('✅✅✅ IMAGEN CARGADA:', this.src); this.style.border='3px solid green';"
                    onerror="console.error('❌❌❌ ERROR EN IMAGEN:', this.src); this.style.border='3px solid red';">
                    

                <div style="display: none;" class="text-muted">
                    <i class="fas fa-image fa-3x mb-2"></i>
                    <p>Imagen no disponible</p>
                </div>
            </div>
        `;
    }
    
    detallesHTML += `
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-hashtag me-2"></i>ID:</span>
            <span class="detail-value fw-bold">#${articulo.id || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-box me-2"></i>Nombre:</span>
            <span class="detail-value">${articulo.nombre || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-cubes me-2"></i>Stock:</span>
            <span class="detail-value">
                <span class="stock-badge ${getStockClass(articulo.stock)}">
                    <i class="fas fa-cubes me-1"></i>
                    ${articulo.stock || 0}
                    ${articulo.stock < 6 ? '<i class="fas fa-exclamation-triangle ms-1"></i>' : ''}
                </span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-barcode me-2"></i>Código:</span>
            <span class="detail-value">${articulo.codigo || 'Sin código'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-cogs me-2"></i>Código Gener:</span>
            <span class="detail-value">${articulo.codigo_gener || 'Sin código'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-tag me-2"></i>Tipo:</span>
            <span class="detail-value">${articulo.tipo_nombre || 'Sin tipo'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-truck me-2"></i>Proveedor:</span>
            <span class="detail-value">${articulo.proveedor_nombre || 'Sin proveedor'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-calendar me-2"></i>Fecha:</span>
            <span class="detail-value">${articulo.fecha || 'No disponible'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-align-left me-2"></i>Descripción:</span>
            <span class="detail-value">${articulo.descripcion || 'Sin descripción'}</span>
        </div>
    `;
    
    contenido.innerHTML = detallesHTML;
    modal.show();
    
    console.log('✅ Modal de detalle mostrado correctamente');
}

// Función auxiliar para clase de stock
function getStockClass(stock) {
    if (stock < 6) return 'stock-low';
    if (stock <= 15) return 'stock-medium';
    return 'stock-high';
}