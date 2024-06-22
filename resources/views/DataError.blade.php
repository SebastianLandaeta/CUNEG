<x-layout>
    @section('title', 'Excel con errores')
    <div class="text-center mb-4">
        <h1>Error en el Excel que intenta cargar</h1>
    </div>
    <div class="container bg-secondary bg-gradient m-2 p-4">
        <p><span style="color: green;"><b>Color verde: </b></span>Indica que el dato está en el formato correcto.</p>
        <p><span style="color: red;"><b>Color rojo: </b></span>Indica que el dato está en el formato incorrecto.</p>
        <p><span style="color: yellow;"><b>Color amarillo: </b></span>Indica que el dato está repetido.</p>

        <p><b>A considerar: </b><p>
        <ul>
            <li>La <b>cedula</b> y el <b>correo electrónico o email</b> no se pueden repetir entre los participantes y/o instructores del curso.</li>
            <li>La <b>cedula</b> debe estar compuesta únicamente por números, sin guiones "-", puntos "." o cualquier otro carácter.</li>
            <li>Los <b>roles</b> que se pueden asumir entre los involucrados en el curso son: <b>"participante"</b> o <b>"instructor"</b> y nada más.</li>
        </ul>
    </div>
    
    <div class="container my-4">
        <h2 class="text-center">Participantes con errores:</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Tipo de documento</th>
                    <th>Numero de Documento</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $participante)
                    <tr>
                        @if (!\App\Helpers\ProcesarParticipantes::valid_tipo_documento($participante['tipo_documento']))
                            <td class="bg-danger">{{ $participante['tipo_documento'] }}</td>
                        @elseif (!\App\Helpers\ProcesarParticipantes::verify_documento_repetido($participante['tipo_documento'], $participante['numero_documento'], $participantes))
                            <td class="bg-warning">{{ $participante['tipo_documento'] }}</td>  
                        @else 
                            <td class="bg-success">{{ $participante['tipo_documento'] }}</td>
                        @endif

                        @if (!\App\Helpers\ProcesarParticipantes::valid_nro_documento($participante['numero_documento']))
                            <td class="bg-danger">{{ $participante['numero_documento'] }}</td>
                        @elseif (!\App\Helpers\ProcesarParticipantes::verify_documento_repetido($participante['tipo_documento'], $participante['numero_documento'], $participantes))
                            <td class="bg-warning">{{ $participante['numero_documento'] }}</td>  
                        @else
                            <td class="bg-success">{{ $participante['numero_documento'] }}</td>
                        @endif 
                        

                        <td class="bg-info">{{ $participante['nombre'] }}</td>
                        <td class="bg-info">{{ $participante['apellido'] }}</td>

                        @if (!\App\Helpers\ProcesarParticipantes::verify_email_repetido($participante['email'], $participantes))
                            <td class="bg-warning">{{ $participante['email'] }}</td>
                        @else 
                            <td class="bg-success">{{ $participante['email'] }}</td>
                        @endif 
                        
                        @if (!\App\Helpers\ProcesarParticipantes::valid_rol($participante['rol']))
                            <td class="bg-danger">{{ $participante['rol'] }}</td>
                        @else
                            <td class="bg-success">{{ $participante['rol'] }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <a href="{{ route('cursos.index') }}" class="btn btn-primary">Volver al listado de cursos</a>
    </div>
</x-layout>
