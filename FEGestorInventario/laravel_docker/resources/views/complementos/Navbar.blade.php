<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Mejorado</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Navbar personalizado con fondo negro elegante */
        .navbar-custom {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border-bottom: 2px solid #333;
            padding: 0.8rem 0;
            transition: all 0.3s ease;
        }

        .navbar-custom:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        /* Marca del navbar con efecto brillante */
        .navbar-brand-custom {
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.4rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            transition: all 0.3s ease;
        }

        .navbar-brand-custom::before {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            transition: width 0.3s ease;
        }

        .navbar-brand-custom:hover::before {
            width: 100%;
        }

        .navbar-brand-custom:hover {
            color: #007bff !important;
            transform: translateY(-1px);
        }

        /* Enlaces del navbar con efectos modernos */
        .nav-link-custom {
            color: #e0e0e0 !important;
            font-weight: 500;
            padding: 0.7rem 1.2rem !important;
            margin: 0 0.2rem;
            border-radius: 6px;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .nav-link-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.6s ease;
        }

        .nav-link-custom:hover::before {
            left: 100%;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background: rgba(0, 123, 255, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .nav-link-custom.active {
            background: #007bff;
            color: #ffffff !important;
            box-shadow: 0 3px 10px rgba(0, 123, 255, 0.4);
        }

        /* Iconos para cada enlace */
        .nav-icon {
            margin-right: 8px;
            font-size: 0.9rem;
        }

        /* Botón toggler personalizado */
        .navbar-toggler-custom {
            border: 2px solid #007bff;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            transition: all 0.3s ease;
        }

        .navbar-toggler-custom:hover {
            background: rgba(0, 123, 255, 0.1);
            border-color: #0056b3;
        }

        .navbar-toggler-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            filter: invert(1);
        }

        /* Animación de entrada */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-custom {
            animation: slideInDown 0.6s ease-out;
        }

        /* Responsive mejorado */
        @media (max-width: 991.98px) {
            .navbar-nav {
                padding-top: 1rem;
                background: rgba(0, 0, 0, 0.9);
                border-radius: 8px;
                margin-top: 0.5rem;
            }
            
            .nav-link-custom {
                margin: 0.2rem 0;
                text-align: center;
            }
        }

        /* Efecto de pulso para elementos activos */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }

        .nav-link-custom.active {
            animation: pulse 2s infinite;
        }


    </style>
</head>
<body>
    <!-- Navbar mejorado -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <!-- Marca con icono -->
            <a class="navbar-brand navbar-brand-custom" href="{{ route('cliente.mostrar') }}">
                <i class="fas fa-cogs me-2"></i>Sistema de Gestión
            </a>
            
            <!-- Botón toggler personalizado -->
            <button class="navbar-toggler navbar-toggler-custom" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Enlaces de navegación -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="{{ route('cliente.mostrar') }}">
                            <i class="fas fa-users nav-icon"></i>Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('proveedores.mostrar') }}">
                            <i class="fas fa-truck nav-icon"></i>Proveedores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('ventas.mostrar') }}">
                            <i class="fas fa-shopping-cart nav-icon"></i>Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('ingresos.mostrar') }}">
                            <i class="fas fa-money-bill-wave nav-icon"></i>Ingresos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('articulos.mostrar') }}">
                            <i class="fas fa-box nav-icon"></i>Artículos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('tipos-articulo.mostrar') }}">
                            <i class="fas fa-tags nav-icon"></i>Tipos de Artículos
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para marcar el enlace activo dinámicamente
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.pathname;
            const menuItems = document.querySelectorAll('.nav-link-custom');
            
            menuItems.forEach(item => {
                if(item.getAttribute('href') === currentLocation) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>