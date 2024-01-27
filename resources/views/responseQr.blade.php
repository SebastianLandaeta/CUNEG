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
                        <h5 class="card-title text-center text-success">Cerficado Valido</h5>

                        <p class="card-text">
                            La UNEG certifica que: <b> {{$participante->nombre}} </b>,
                            titular de la cedula: <b> {{$participante->cedula }} </b>,
                            participo y aprobo satisfactoriamente en nuestro curso impartido de: {{$curso->nombre}}.
                        </p>

                        <p class="card-text">
                            Mas detalles del curso: 
                            <div class="container">
                            Descripcion del curso: {{$curso->descripcion}}. <br>
                            Instructor: -------- <br>  
                            Fecha de Inicio: {{\Carbon\Carbon::parse($curso->f_inicio)->format('d-m-Y')}}. <br>
                            Fecha de Finalizacion: {{\Carbon\Carbon::parse($curso->f_finalizacion)->format('d-m-Y')}}. <br>
                            </div>
                        </p>

                        <div class="container d-flex justify-content-center">
                            <a href="#" class="btn btn-outline-success">Descargar Certificado</a>
                        </div>

                    </div>
                    <div class="card-footer text-bg-success text-body-secondary">
                    Consulta echa el dia: {{ now()->format('d-m-Y') }}
                    </div>
                </div>

                <div class="container d-flex justify-content-center mt-3">
                            <a href="{{ route('index') }}" class="btn btn-outline-primary">Menu de inicio</a>
                </div>
        @else
            <div class="card  border-danger">
                    <div class="card-header text-bg-danger text-center">
                        Universidad Nacional Experimental de Guayana
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center text-danger">Cerficado Invalido</h5>

                        <p class="card-text">
                            Los detalles proporcionados en la consulta no coinciden con la base de datos de CUNEG,
                            por lo que no podemos identificar información correspondiente al certificado solicitado.
                        </p>

                        <div class="container d-flex justify-content-center">
                            <a href="{{ route('index') }}" class="btn btn-outline-primary">Menu de inicio</a>
                        </div>

                    </div>

                    <div class="card-footer text-bg-danger text-body-secondary">
                        Consulta echa el dia: {{ now()->format('d-m-Y') }}
                    </div>
                </div>
        @endif
    </div>
</x-layout>