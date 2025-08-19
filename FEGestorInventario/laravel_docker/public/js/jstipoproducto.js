document.addEventListener('DOMContentLoaded', function () {
    // Evento para modal de editar tipo de producto
    var formModal = document.getElementById('formModal');

    if (formModal) {
        formModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id = button.getAttribute('data-id');
            var nombre = button.getAttribute('data-nombre');

            var form = formModal.querySelector('#form-editar');

            // Actualizar la action del formulario
            form.action = form.action.replace('__id__', id);

            // Llenar los campos del formulario
            form.querySelector('#nombre').value = nombre;
        });
    }

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

    // Animación de entrada para las filas de la tabla
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });

    // Función para mostrar detalle del tipo de producto
    window.verDetalleTipo = function(tipo) {
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleTipo'));
        const contenido = document.getElementById('contenidoDetalleTipo');
        
        let detallesHTML = `
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-uppercase fw-bold mb-3" style="color: var(--color-primary);">
                        <i class="fas fa-info-circle me-2"></i>Información del Tipo de Producto
                    </h6>
                </div>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-hashtag me-2"></i>ID:</span>
                <span class="detail-value fw-bold">#${tipo.id || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-tag me-2"></i>Nombre:</span>
                <span class="detail-value">${tipo.nombre || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar me-2"></i>Fecha de creación:</span>
                <span class="detail-value">${tipo.created_at ? new Date(tipo.created_at).toLocaleDateString() : 'No disponible'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-box me-2"></i>Productos asociados:</span>
                <span class="detail-value">${tipo.productos_count || '0'} productos</span>
            </div>
        `;
        
        contenido.innerHTML = detallesHTML;
        modal.show();
    };

    // Validación de formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const nombre = form.querySelector('input[name="nombre"]');

            let valid = true;
            let message = '';

            // Validar nombre
            if (nombre && (!nombre.value || nombre.value.trim().length < 2)) {
                valid = false;
                message += 'El nombre debe tener al menos 2 caracteres.\n';
            }

            // Validar que no tenga caracteres especiales peligrosos
            if (nombre && nombre.value) {
                const invalidChars = /[<>\"'&]/;
                if (invalidChars.test(nombre.value)) {
                    valid = false;
                    message += 'El nombre no puede contener caracteres especiales como < > " \' &.\n';
                }
            }

            // Validar longitud máxima
            if (nombre && nombre.value && nombre.value.trim().length > 50) {
                valid = false;
                message += 'El nombre no puede tener más de 50 caracteres.\n';
            }

            if (!valid) {
                e.preventDefault();
                alert(message);
                return false;
            }
        });
    });

    // Función para filtrar la tabla (búsqueda en tiempo real)
    window.filtrarTipos = function(searchTerm) {
        const rows = document.querySelectorAll('tbody tr');
        const lowerSearchTerm = searchTerm.toLowerCase();
        
        rows.forEach(function(row) {
            const id = row.cells[0].textContent.toLowerCase();
            const nombre = row.cells[1].textContent.toLowerCase();
            
            const shouldShow = id.includes(lowerSearchTerm) ||
                             nombre.includes(lowerSearchTerm);
            
            row.style.display = shouldShow ? '' : 'none';
        });
        
        // Mostrar mensaje si no hay resultados
        const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])');
        const noResultsMsg = document.getElementById('noResultsMessage');
        
        if (visibleRows.length === 0) {
            if (!noResultsMsg) {
                const tbody = document.querySelector('tbody');
                const messageRow = document.createElement('tr');
                messageRow.id = 'noResultsMessage';
                messageRow.innerHTML = `
                    <td colspan="3" class="text-center py-4">
                        <i class="fas fa-search me-2"></i>
                        No se encontraron tipos que coincidan con "${searchTerm}"
                    </td>
                `;
                tbody.appendChild(messageRow);
            }
        } else {
            if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }
    };

    // Agregar campo de búsqueda si no existe
    const pageHeader = document.querySelector('.page-header');
    if (pageHeader && !document.querySelector('#searchTipos')) {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'mt-3';
        searchContainer.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchTipos" class="form-control border-start-0" 
                               placeholder="Buscar por ID o nombre del tipo...">
                    </div>
                </div>
            </div>
        `;
        
        pageHeader.appendChild(searchContainer);
        
        // Agregar evento de búsqueda
        const searchInput = document.getElementById('searchTipos');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filtrarTipos(this.value);
            });
        }
    }

    // Confirmación para acciones destructivas (si se implementan)
    window.confirmarEliminacion = function(nombre) {
        return confirm(`¿Está seguro de que desea eliminar el tipo "${nombre}"?\n\nEsta acción no se puede deshacer y puede afectar a los productos asociados.`);
    };

    // Función para generar icono basado en el nombre del tipo
    window.generarIconoTipo = function(nombre) {
        const iconos = {
            'electronico': 'fas fa-laptop',
            'electrónico': 'fas fa-laptop',
            'ropa': 'fas fa-tshirt',
            'vestimenta': 'fas fa-tshirt',
            'comida': 'fas fa-utensils',
            'alimento': 'fas fa-utensils',
            'bebida': 'fas fa-glass-cheers',
            'libro': 'fas fa-book',
            'juguete': 'fas fa-gamepad',
            'hogar': 'fas fa-home',
            'casa': 'fas fa-home',
            'deporte': 'fas fa-dumbbell',
            'deportivo': 'fas fa-dumbbell',
            'salud': 'fas fa-heartbeat',
            'belleza': 'fas fa-spa',
            'herramienta': 'fas fa-tools',
            'auto': 'fas fa-car',
            'vehiculo': 'fas fa-car',
            'música': 'fas fa-music',
            'musica': 'fas fa-music',
            'oficina': 'fas fa-briefcase',
            'jardin': 'fas fa-seedling',
            'mascota': 'fas fa-paw'
        };
        
        const nombreLower = nombre.toLowerCase();
        for (const [key, icon] of Object.entries(iconos)) {
            if (nombreLower.includes(key)) {
                return icon;
            }
        }
        
        // Icono por defecto
        return 'fas fa-tag';
    };

    // Aplicar iconos a los tipos existentes
    const tipoElements = document.querySelectorAll('.tipo-name');
    tipoElements.forEach(function(elemento) {
        const nombre = elemento.textContent.trim();
        const icono = generarIconoTipo(nombre);
        elemento.innerHTML = `<i class="${icono} me-2"></i>${nombre}`;
    });

    // Mejorar UX con loading states
    const buttons = document.querySelectorAll('button[type="submit"]');
    buttons.forEach(function(button) {
        const form = button.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                
                // Reactivar después de 5 segundos como fallback
                setTimeout(function() {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }, 5000);
            });
        }
    });

    // Función para ordenar la tabla
    window.ordenarTabla = function(columna) {
        const tabla = document.querySelector('table tbody');
        const filas = Array.from(tabla.querySelectorAll('tr'));
        
        filas.sort(function(a, b) {
            let aVal, bVal;
            
            if (columna === 'id') {
                aVal = parseInt(a.cells[0].textContent.replace('#', ''));
                bVal = parseInt(b.cells[0].textContent.replace('#', ''));
            } else if (columna === 'nombre') {
                aVal = a.cells[1].textContent.toLowerCase();
                bVal = b.cells[1].textContent.toLowerCase();
            }
            
            if (aVal < bVal) return -1;
            if (aVal > bVal) return 1;
            return 0;
        });
        
        // Limpiar tabla y reagregar filas ordenadas
        tabla.innerHTML = '';
        filas.forEach(fila => tabla.appendChild(fila));
    };

    // Función para estadísticas simples
    window.mostrarEstadisticasTipos = function() {
        const totalTipos = document.querySelectorAll('tbody tr').length;
        const tiposVisibles = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;
        
        console.log(`Estadísticas de Tipos de Producto:
        - Total de tipos: ${totalTipos}
        - Tipos visibles: ${tiposVisibles}`);
        
        if (totalTipos > 0) {
            alert(`Estadísticas:\n- Total de tipos: ${totalTipos}\n- Tipos visibles: ${tiposVisibles}`);
        }
    };

    // Añadir tooltips a los botones
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});