<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/jpg" href="http://2.bp.blogspot.com/_HWxhcqu_65c/S8CkLsUhJpI/AAAAAAAAA6g/F0sF9duOxLo/s1600/LOGO_UNEG[1].jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>@yield('title')</title>
</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="{{ route('index') }}"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <img src="http://2.bp.blogspot.com/_HWxhcqu_65c/S8CkLsUhJpI/AAAAAAAAA6g/F0sF9duOxLo/s1600/LOGO_UNEG[1].jpg" alt="Custom Icon" width="40" height="40" class="me-2">
                <span class="fs-4">CUNEG</span>
            </a>

            <ul class="nav nav-pills">
                <li class="nav-item"><a href="{{ route('index') }}" class="nav-link" aria-current="page">Inicio</a></li>
                <li class="nav-item"><a href="{{ route('cursos') }}" class="nav-link">Cursos</a></li>
                <li class="nav-item"><a href="{{ route('qr.search') }}" class="nav-link">Consulta de Certificado</a></li>
                <li class="nav-item"><a href="#" class="nav-link">FAQs</a></li>
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
                <li class="nav-item"><a href="#" class="nav-link active">About</a></li>
            </ul>
        </header>

        {{ $slot }}

        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center">
              <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
              </a>
              <span class="mb-3 mb-md-0 text-body-secondary">Universidad Nacional Experimental de Guayana - Todos los derechos reservados</span>
            </div>

            <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
              <li class="ms-3"><a class="text-body-secondary" href="https://twitter.com/Uneginforma" target="_blank"><i class="fab fa-twitter"></i></a></li>
              <li class="ms-3"><a class="text-body-secondary" href="https://www.instagram.com/uneg_oficial/" target="_blank"><i class="fab fa-instagram"></i></a></li>
              <li class="ms-3"><a class="text-body-secondary" href="https://www.facebook.com/uneguayana" target="_blank"><i class="fab fa-facebook"></i></a></li>
            </ul>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
