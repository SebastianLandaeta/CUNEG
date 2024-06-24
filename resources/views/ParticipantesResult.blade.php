<x-layout>
    @section('title', 'Resultado de carga')
    <div class="container">
        <h2>Resultados de la Carga de Participantes</h2>

        @if(count($newParticipants) > 0)
            <h3>Nuevos Participantes</h3>
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
                    @foreach ($newParticipants as $participante)
                        <tr>
                            <td>{{ $participante['data']['tipo_documento']}}</td>
                            <td>{{ $participante['data']['numero_documento'] }}</td>
                            <td>{{ $participante['data']['nombre'] }}</td>
                            <td>{{ $participante['data']['apellido'] }}</td>
                            <td>{{ $participante['data']['email'] }}</td>
                            <td>{{ $participante['rol'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if(count($updatedParticipants) > 0)
        <table class="table">
            <h3>Participantes actualizados</h3>
            <p>Ya estaban en la base de datos de CUNEG</p>
            <thead>
                <tr>
                    <th>Tipo de documento</th>
                    <th>Numero de Documento</th>
                    <th>Nombre </th>
                    <th>Apellido </th>
                    <th>Email </th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($updatedParticipants as $participante)
                    <tr>
                        <td>{{ $participante['oldAttributes']['tipo_documento'] }}</td>
                        <td>{{ $participante['oldAttributes']['numero_documento'] }}</td>

                        @if ( $participante['oldAttributes']['nombre'] != $participante['newAttributes']['nombre'] )
                            <td>{{ $participante['oldAttributes']['nombre'] }} -> {{ $participante['newAttributes']['nombre'] }}</td>
                        @else 
                            <td>{{ $participante['newAttributes']['nombre'] }}</td>
                        @endif

                        @if ($participante['oldAttributes']['apellido']  !=  $participante['newAttributes']['apellido'])
                            <td>{{ $participante['oldAttributes']['apellido'] }} -> {{ $participante['newAttributes']['apellido'] }}</td>
                        @else 
                            <td>{{ $participante['newAttributes']['apellido'] }}</td>
                        @endif
                        

                        @if ($participante['oldAttributes']['email']  !=  $participante['newAttributes']['email'])
                            <td>{{ $participante['oldAttributes']['email'] }} -> {{ $participante['newAttributes']['email'] }}</td>
                        @else 
                            <td>{{ $participante['newAttributes']['email'] }}</td>
                        @endif 


                        <td >{{ $participante['rol'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if(count($failedParticipants) > 0)
            <h3>Participantes no Agregados (gestionar backend)</h3>
        @endif

        
        @if(count($invalidParticipants) > 0)
        <h3>Participantes Inválidos</h3>
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
                @foreach ($invalidParticipants as $participante)
                        <tr>
                            @if (!\App\Helpers\ProcesarParticipantes::valid_tipo_documento($participante['tipo_documento']))
                                <td class="bg-danger">{{ $participante['tipo_documento'] }}</td>
                            @elseif (!\App\Helpers\ProcesarParticipantes::verify_documento_repetido($participante['tipo_documento'], $participante['numero_documento'],$invalidParticipants))
                                <td class="bg-warning">{{ $participante['tipo_documento'] }}</td>  
                            @else 
                                <td class="bg-success">{{ $participante['tipo_documento'] }}</td>
                            @endif

                            @if (!\App\Helpers\ProcesarParticipantes::valid_nro_documento($participante['numero_documento']))
                                <td class="bg-danger">{{ $participante['numero_documento'] }}</td>
                            @elseif (!\App\Helpers\ProcesarParticipantes::verify_documento_repetido($participante['tipo_documento'], $participante['numero_documento'], $invalidParticipants))
                                <td class="bg-warning">{{ $participante['numero_documento'] }}</td>  
                            @else
                                <td class="bg-success">{{ $participante['numero_documento'] }}</td>
                            @endif 
                            

                            <td class="bg-info">{{ $participante['nombre'] }}</td>
                            <td class="bg-info">{{ $participante['apellido'] }}</td>

                            @if (!\App\Helpers\ProcesarParticipantes::verify_email_repetido($participante['email'], $invalidParticipants))
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
        @endif
        

        <div class="text-center">
            <a href="{{ route('cursos.index') }}" class="btn btn-primary">Volver al listado de cursos</a>
        </div>
    </div>
</x-layout>
