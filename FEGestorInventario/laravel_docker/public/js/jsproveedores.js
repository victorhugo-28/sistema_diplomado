document.addEventListener('DOMContentLoaded', function () {
    // Evento para modal de editar proveedor
    var formModal = document.getElementById('formModal');

    if (formModal) {
        formModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id = button.getAttribute('data-id');
            var nombre = button.getAttribute('data-nombre');
            var contacto = button.getAttribute('data-contacto');
            var direccion = button.getAttribute('data-direccion');

            var form = formModal.querySelector('#form-editar');

            // Actualizar la action del formulario
            form.action = form.action.replace('__id__', id);

            // Llenar los campos del formulario
            form.querySelector('#nombre').value = nombre;
            form.querySelector('#contacto').value = contacto;
            form.querySelector('#direccion').value = direccion;
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

    // Función para mostrar detalle del proveedor (si se necesita en el futuro)
    window.verDetalleProveedor = function(proveedor) {
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleProveedor'));
        const contenido = document.getElementById('contenidoDetalleProveedor');
        
        let detallesHTML = `
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-uppercase fw-bold mb-3" style="color: var(--color-primary);">
                        <i class="fas fa-info-circle me-2"></i>Información del Proveedor
                    </h6>
                </div>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-hashtag me-2"></i>ID:</span>
                <span class="detail-value fw-bold">#${proveedor.id || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-building me-2"></i>Nombre:</span>
                <span class="detail-value">${proveedor.nombre || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-user me-2"></i>Contacto:</span>
                <span class="detail-value">${proveedor.contacto || 'Sin contacto'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-map-marker-alt me-2"></i>Dirección:</span>
                <span class="detail-value">${proveedor.direccion || 'Sin dirección'}</span>
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
            const contacto = form.querySelector('input[name="contacto"]');
            const direccion = form.querySelector('input[name="direccion"]');

            let valid = true;
            let message = '';

            // Validar nombre
            if (nombre && (!nombre.value || nombre.value.trim().length < 2)) {
                valid = false;
                message += 'El nombre debe tener al menos 2 caracteres.\n';
            }

            // Validar contacto
            if (contacto && contacto.value) {
                // Validar si parece un email o teléfono
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const phoneRegex = /^[0-9+\-\s()]{7,}$/;
                
                if (!emailRegex.test(contacto.value) && !phoneRegex.test(contacto.value)) {
                    valid = false;
                    message += 'El contacto debe ser un email válido o un teléfono válido.\n';
                }
            }

            // Validar dirección
            if (direccion && direccion.value && direccion.value.trim().length < 5) {
                valid = false;
                message += 'La dirección debe tener al menos 5 caracteres.\n';
            }

            if (!valid) {
                e.preventDefault();
                alert(message);
                return false;
            }
        });
    });

    // Función para filtrar la tabla (búsqueda en tiempo real)
    window.filtrarProveedores = function(searchTerm) {
        const rows = document.querySelectorAll('tbody tr');
        const lowerSearchTerm = searchTerm.toLowerCase();
        
        rows.forEach(function(row) {
            const id = row.cells[0].textContent.toLowerCase();
            const nombre = row.cells[1].textContent.toLowerCase();
            const contacto = row.cells[2].textContent.toLowerCase();
            const direccion = row.cells[3].textContent.toLowerCase();
            
            const shouldShow = id.includes(lowerSearchTerm) ||
                             nombre.includes(lowerSearchTerm) || 
                             contacto.includes(lowerSearchTerm) || 
                             direccion.includes(lowerSearchTerm);
            
            row.style.display = shouldShow ? '' : 'none';
        });
    };

    // Agregar campo de búsqueda si no existe
    const pageHeader = document.querySelector('.page-header');
    if (pageHeader && !document.querySelector('#searchProveedores')) {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'mt-3';
        searchContainer.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchProveedores" class="form-control border-start-0" 
                               placeholder="Buscar por ID, nombre, contacto o dirección...">
                    </div>
                </div>
            </div>
        `;
        
        pageHeader.appendChild(searchContainer);
        
        // Agregar evento de búsqueda
        const searchInput = document.getElementById('searchProveedores');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filtrarProveedores(this.value);
            });
        }
    }

    // Confirmación para acciones destructivas (si se implementan)
    window.confirmarEliminacion = function(nombre) {
        return confirm(`¿Está seguro de que desea eliminar el proveedor "${nombre}"?\n\nEsta acción no se puede deshacer.`);
    };

    // Mejorar UX con loading states
    const buttons = document.querySelectorAll('button[type="submit"]');
    buttons.forEach(function(button) {
        const form = button.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                
                // Reactivar después de 5 segundos como fallback
                setTimeout(function() {
                    button.disabled = false;
                    button.innerHTML = button.innerHTML.replace(
                        '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...', 
                        '<i class="fas fa-save me-2"></i>Guardar'
                    );
                }, 5000);
            });
        }
    });

    // Funciones de utilidad para formatear datos
    window.formatearContacto = function(contacto) {
        if (!contacto) return 'Sin contacto';
        
        // Si parece un email
        if (contacto.includes('@')) {
            return `<i class="fas fa-envelope me-1"></i>${contacto}`;
        }
        
        // Si parece un teléfono
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (phoneRegex.test(contacto)) {
            return `<i class="fas fa-phone me-1"></i>${contacto}`;
        }
        
        // Contacto genérico
        return `<i class="fas fa-user me-1"></i>${contacto}`;
    };

    window.formatearDireccion = function(direccion) {
        if (!direccion) return 'Sin dirección';
        
        // Truncar dirección larga
        if (direccion.length > 50) {
            return direccion.substring(0, 47) + '...';
        }
        
        return direccion;
    };
});