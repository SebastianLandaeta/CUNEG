<x-layout>
    @section('title', 'Respuesta de consulta QR')

    <div class="container">
        <h1>Respuesta de su consulta</h1>

        
        <p>ID Curso: {{ $cursoId }}</p>
        <p>ID Participante: {{ $participanteId }}</p>

        @if (isset($curso))
            <h2>Información del Curso</h2>

            <p>ID del Curso: {{ $curso->id }}</p>
            <p>Nombre del Curso: {{ $curso->nombre }}</p>

            <!-- Mostrar más detalles del curso si es necesario -->
            
        @else
            <p>No se encontró información del curso.</p>
        @endif

        @if (isset($participante))
            <h2>Información del Participante</h2>
            <p>ID del Participante: {{ $participante->cedula }}</p>
            <p>Nombre del Participante: {{ $participante->nombre }} {{ $participante->apellido }}</p>
            <!-- Mostrar más detalles del participante si es necesario -->

        @else
            <p>No se encontró información del participante.</p>
        @endif
        
        @if (isset($participanteEnCurso))
            <p>El participante está asociado a este curso.</p>
        @else
            <p>El participante no está asociado a este curso.</p>
        @endif
    </div>
</x-layout>