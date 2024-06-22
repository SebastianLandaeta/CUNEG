<x-layout>
    @section('title', 'Resultado de carga')
    <div class="container">
        <h2>Resultados de la Carga de Participantes</h2>
        
        <h3>Nuevos Participantes</h3>
        @if(count($newParticipants) > 0)
            <ul>
                @foreach($newParticipants as $participante)
                    <li>{{ $participante->nombre }} {{ $participante->apellido }}</li>
                @endforeach
            </ul>
        @else
            <p>No hay nuevos participantes.</p>
        @endif

        <h3>Participantes Actualizados</h3>
        @if(count($updatedParticipants) > 0)
            <ul>
                @foreach($updatedParticipants as $participante)
                    <li>{{ $participante->nombre }} {{ $participante->apellido }}</li>
                @endforeach
            </ul>
        @else
            <p>No hay participantes actualizados.</p>
        @endif

        <h3>Participantes no Agregados</h3>
        @if(count($failedParticipants) > 0)
            <ul>
                @foreach($failedParticipants as $participante)
                    <li>{{ $participante->nombre }} {{ $participante->apellido }}</li>
                @endforeach
            </ul>
        @else
            <p>No hay participantes no agregados.</p>
        @endif

        <h3>Participantes Inválidos</h3>
        @if(count($invalidParticipants) > 0)
            <ul>
                @foreach($invalidParticipants as $participante)
                    <li>{{ $participante['nombre'] }} {{ $participante['apellido'] }} - Error en tipo de documento o rol.</li>
                @endforeach
            </ul>
        @else
            <p>No hay participantes inválidos.</p>
        @endif

        <div class="text-center">
            <a href="{{ route('cursos.index') }}" class="btn btn-primary">Volver al listado de cursos</a>
        </div>
    </div>
</x-layout>
