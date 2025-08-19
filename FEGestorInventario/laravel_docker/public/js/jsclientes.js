document.addEventListener('DOMContentLoaded', function () {
    // Evento para modal de editar cliente
    var formModal = document.getElementById('formModal');

    if (formModal) {
        formModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id = button.getAttribute('data-id');
            var nombre = button.getAttribute('data-nombre');
            var email = button.getAttribute('data-email');
            var telefono = button.getAttribute('data-telefono');

            var form = formModal.querySelector('#form-editar');

            // Actualizar la action del formulario
            form.action = form.action.replace('__id__', id);

            // Llenar los campos del formulario
            form.querySelector('#nombre').value = nombre;
            form.querySelector('#email').value = email;
            form.querySelector('#telefono').value = telefono;
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

    // Función para mostrar detalle del cliente (si se necesita en el futuro)
    window.verDetalleCliente = function(cliente) {
        console.log('Detalle del cliente:', cliente);
        // Aquí puedes implementar un modal de detalle si lo necesitas
    };

    // Validación de formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const nombre = form.querySelector('input[name="nombre"]');
            const email = form.querySelector('input[name="email"]');
            const telefono = form.querySelector('input[name="telefono"]');

            let valid = true;
            let message = '';

            // Validar nombre
            if (nombre && (!nombre.value || nombre.value.trim().length < 2)) {
                valid = false;
                message += 'El nombre debe tener al menos 2 caracteres.\n';
            }

            // Validar email
            if (email && email.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    valid = false;
                    message += 'El email no tiene un formato válido.\n';
                }
            }

            // Validar teléfono
            if (telefono && telefono.value) {
                const phoneRegex = /^[0-9+\-\s()]{7,}$/;
                if (!phoneRegex.test(telefono.value)) {
                    valid = false;
                    message += 'El teléfono debe tener un formato válido (mínimo 7 dígitos).\n';
                }
            }

            if (!valid) {
                e.preventDefault();
                alert(message);
                return false;
            }
        });
    });

    // Función para filtrar la tabla (búsqueda en tiempo real)
    window.filtrarClientes = function(searchTerm) {
        const rows = document.querySelectorAll('tbody tr');
        const lowerSearchTerm = searchTerm.toLowerCase();
        
        rows.forEach(function(row) {
            const nombre = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const telefono = row.cells[2].textContent.toLowerCase();
            
            const shouldShow = nombre.includes(lowerSearchTerm) || 
                             email.includes(lowerSearchTerm) || 
                             telefono.includes(lowerSearchTerm);
            
            row.style.display = shouldShow ? '' : 'none';
        });
    };

    // Agregar campo de búsqueda si no existe
    const pageHeader = document.querySelector('.page-header');
    if (pageHeader && !document.querySelector('#searchClientes')) {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'mt-3';
        searchContainer.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchClientes" class="form-control border-start-0" 
                               placeholder="Buscar por nombre, email o teléfono...">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-white-50">
                        <i class="fas fa-info-circle me-1"></i>
                        Total de clientes: ${document.querySelectorAll('tbody tr').length}
                    </small>
                </div>
            </div>
        `;
        
        pageHeader.appendChild(searchContainer);
        
        // Agregar evento de búsqueda
        const searchInput = document.getElementById('searchClientes');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filtrarClientes(this.value);
            });
        }
    }

    // Confirmación para acciones destructivas (si se implementan)
    window.confirmarEliminacion = function(nombre) {
        return confirm(`¿Está seguro de que desea eliminar el cliente "${nombre}"?\n\nEsta acción no se puede deshacer.`);
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
});