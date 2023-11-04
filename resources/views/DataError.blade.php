<x-layout>
    @section('title', 'Excel con errores')
    <div class="text-center mb-4">
        <h1>Error en el Excel que intenta cargar</h1>
    </div>
    <div class="container bg-secondary bg-gradient m-2 p-4">
        <p><span style="color: green;"><b>Color verde: </b></span>Indica que el dato esta en el formato correcto.</p>
        <p><span style="color: red;"><b>Color rojo: </b></span>Indica que el dato esta en el formato incorrecto.</p>
        <p><span style="color: yellow;"><b>Color amarillo: </b></span>Indica que el dato esta repetido.</p>

        <p><b>A considerar: </b><p>
        <ul>
            <li>La <b>cedula</b> y el <b>correo electronico o email</b>  no se pueden repetir entre los participantes y/o instructores del curso.</li>
            <li>La <b>cedula</b> debe estar compuesta unicamente por numeros, sin guiones "-", puntos "." o cualquier otro caracter.</li>
            <li>Los <b>roles</b> que se pueden asumir entre los involucrados en el curso son: <b>"participante"</b> o <b>"instructor"</b> y nada mas.</li>
        </ul>
    </div>
    
    <div class="container my-4">
        <h2 class="text-center">Participantes con errores:</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $participante)
                    <tr>
                        @if (app('App\Helpers\FileProcessing\FileProcessing')->valid_ci($participante['cedula']) == false)
                            <td class="bg-danger">
                            {{ $participante['cedula'] }}
                            </td>
                        @elseif (app('App\Helpers\FileProcessing\FileProcessing')->verify_ci($participante['cedula'],$participantes) == false)
                            <td class="bg-warning">
                            {{ $participante['cedula'] }}
                            </td>        
                        @else
                            <td class="bg-success">
                            {{ $participante['cedula'] }}
                            </td>
                        @endif

                        <td class="bg-info">{{ $participante['nombre'] }}</td>
                        <td class="bg-info">{{ $participante['apellido'] }}</td>

                        @if (app('App\Helpers\FileProcessing\FileProcessing')->verify_email($participante['email'],$participantes) == false)
                            <td class="bg-warning">
                            {{ $participante['email'] }}
                            </td>
                        @else 
                            <td class="bg-success">
                            {{ $participante['email'] }}
                            </td>
                        @endif 
                        
                        @if (app('App\Helpers\FileProcessing\FileProcessing')->valid_rol($participante['rol']))
                            
                            <td class="bg-success">
                            {{ $participante['rol'] }}
                            </td>
                        @else
                            
                            <td class="bg-danger">
                            {{ $participante['rol'] }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <a href="{{ route('cursos') }}" class="btn btn-primary">Volver al listado de cursos</a>
    </div>
</x-layout>
