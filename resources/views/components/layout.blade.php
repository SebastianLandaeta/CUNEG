<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/jpg" href="http://2.bp.blogspot.com/_HWxhcqu_65c/S8CkLsUhJpI/AAAAAAAAA6g/F0sF9duOxLo/s1600/LOGO_UNEG[1].jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <li class="nav-item"><a href="{{ route('cursos.index') }}" class="nav-link">Cursos</a></li>
                <li class="nav-item"><a href="{{ route('qr.search') }}" class="nav-link">Consulta de Certificado</a></li>
                <!--<li class="nav-item"><a href="#" class="nav-link">FAQs</a></li>
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
                <li class="nav-item"><a href="#" class="nav-link active">About</a></li>-->
            </ul>
        </header>

        {{ $slot }}

        <footer class="py-3 my-4 border-top">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-6 col-sm-12 mb-3 mb-sm-0 text-center text-sm-start">
                        <span class="text-body-secondary">Universidad Nacional Experimental de Guayana - Todos los derechos reservados</span>
                    </div>

                    <div class="col-md-6 col-sm-12 d-flex justify-content-center justify-content-sm-end">
                        <ul class="nav list-unstyled">
                            <li class=""><a class="text-body-secondary" href="https://twitter.com/Uneginforma" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li class="ms-2"><a class="text-body-secondary" href="https://www.instagram.com/uneg_oficial/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            <li class="ms-2"><a class="text-body-secondary" href="https://www.facebook.com/uneguayana" target="_blank"><i class="fab fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
</body>

</html>
