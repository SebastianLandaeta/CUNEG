<x-layout>
    @section('title', 'Respuesta de consulta QR')

    <div class="container">
        <h1 class="text-center mb-4">Respuesta de consulta QR:</h1>

        @if ($participanteEnCurso==true)
            <div class="card border-success">
                <div class="card-header text-bg-success text-center">
                    Universidad Nacional Experimental de Guayana
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center text-success">Cerficado Válido</h5>
                        <p class="card-text">
                            La UNEG certifica que: <b> {{$participante->nombre}}</b>,
                            titular de la cedula: <b> {{$participante->cedula }}</b>,
                            participó en nuestro curso de: {{$curso->nombre}}.
                        </p>

                        <p class="card-text">
                            Más detalles del curso:
                            <div class="container">
                            Descripción del curso: {{$curso->descripcion}}. <br>
                            Fecha de Inicio: {{\Carbon\Carbon::parse($curso->f_inicio)->format('d-m-Y')}}. <br>
                            Fecha de Finalización: {{\Carbon\Carbon::parse($curso->f_finalizacion)->format('d-m-Y')}}. <br>
                            </div>
                        </p>
                </div>
                    <div class="card-footer text-bg-success">
                    Consulta hecha el día: {{ now()->format('d-m-Y') }}
                    </div>
                </div>

                <div class="container d-flex justify-content-center mt-3">
                    <a href="{{ route('index') }}" class="btn btn-outline-primary">Menú de inicio</a>
                </div>
        @else
            <div class="card  border-danger">
                <div class="card-header text-bg-danger text-center">
                    Universidad Nacional Experimental de Guayana
                </div>
                <div class="card-body">
                        <h5 class="card-title text-center text-danger">Certificado Inválido</h5>

                        <p class="card-text">
                            Los detalles proporcionados en la consulta no coinciden con la base de datos de CUNEG,
                            por lo que no podemos identificar información correspondiente al certificado solicitado.
                        </p>

                        <div class="container d-flex justify-content-center">
                            <a href="{{ route('index') }}" class="btn btn-outline-primary">Menú de inicio</a>
                        </div>

                    </div>

                    <div class="card-footer text-bg-danger text-body-secondary">
                        Consulta hecha el día: {{ now()->format('d-m-Y') }}
                    </div>
            </div>
        @endif
    </div>
</x-layout>
