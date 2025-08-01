<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar de Clientes</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJXgKJzYk+zD4ZRHj3DTFuqmflY4V6VrZd5cPRbbK8nS1fQ3c5jSY2d6y52r" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Clientes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"navbarNav">
                <ul class="navbar-nav">
                    <!-- Opción Crear Cliente -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('/') }}">Crear Cliente</a>
                    </li>
                    <!-- Opción Lista de Clientes -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('/prueba') }}">Lista de Clientes</a>
                    </li>
               >
            </div>
        </div>
    </nav>

    <!-- Contenido de la página -->
    @yield('content')

    <!-- Incluir Bootstrap JS (para la funcionalidad del navbar en dispositivos pequeños) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb6F6k2tPsHfhQxMEdz4uP2PTevK5a4x4pI5s76p3xwV3CC71" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0A3z3X8kW9c6riF3vBv5bqL2bfoy6ikPpftz4wJqVhaIZtD2" crossorigin="anonymous"></script>
</body>
</html>
