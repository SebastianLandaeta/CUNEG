<x-layout>
    @section('title', 'CUNEG')
    <div class="px-4 py-5 my-5 text-center">
        <img class="d-block mx-auto mb-4" src="{{ asset('assets/LOGO_UNEG.jpg') }}" alt="Logo UNEG" width="150" height="150">
        <h1 class="display-5 fw-bold text-body-emphasis">Sistema Generador y Validador de Certificados UNEG</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">Nuestra plataforma es una solución innovadora que agiliza la emisión y validación de certificados en la Universidad Nacional Experimental de Guayana (UNEG), simplificando la acreditación de actividades académicas y comunitarias.</p>
            
        </div>
        <div class="col-lg-6 mx-auto mt-4">
            <p class="lead mb-4">Guiados por la experiencia de Kelvin Cárima y la asesoría de Ana Huamán,
                nuestro equipo de estudiantes de Ingeniería en Informática
                - busca proporcionar una herramienta efectiva que beneficie a estudiantes, 
                profesores y personal administrativo en la UNEG.</p>
        </div>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="{{ route('cursos') }}" class="btn btn-primary btn-lg px-4 gap-3">Menú de cursos</a>
            </div>
    </div>
</x-layout>

