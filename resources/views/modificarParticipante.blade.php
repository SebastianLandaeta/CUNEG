<x-layout>
    @section('title', 'Modificar participante')

    <div class="container d-flex justify-content-center align-items-center">
        <div class="col-md-6">
            <form id="modificarParticipanteForm" action="{{ route('participante.actualizar', ['participante' => $participante]) }}" method="POST">
                @csrf
                @method('PUT')
                <p><b>Datos Generales</b></p>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ $participante->nombre }}">
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" value="{{ $participante->apellido }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $participante->email }}">
                </div>

                <div class="mb-3">
                    <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                    <select id="tipo_documento" name="tipo_documento" class="form-control">
                        @foreach ($documentos_validos as $documento)
                            <option value="{{ $documento }}" {{ $participante->tipo_documento == $documento ? 'selected' : '' }}>{{ $documento }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="numero_documento" class="form-label">Número de Documento:</label>
                    <input type="text" id="numero_documento" name="numero_documento" class="form-control" value="{{ $participante->numero_documento }}">
                </div>

                <div class="mb-3">
                    <label for="roles"><b>Roles por curso:</b></label>
                    <div id="rolesPorCurso">
                        @foreach ($participante->cursos as $curso)
                            <div class="mb-2">
                                <label for="rol{{ $curso->id }}" class="form-label">{{ $curso->nombre }}</label>
                                <select id="rol{{ $curso->id }}" name="roles[{{ $curso->id }}]" class="form-control">
                                    <option value="Participante" {{ $participante->cursos->find($curso->id)->pivot->rol == 'Participante' ? 'selected' : '' }}>Participante</option>
                                    <option value="Instructor" {{ $participante->cursos->find($curso->id)->pivot->rol == 'Instructor' ? 'selected' : '' }}>Instructor</option>
                                    <option value="Facilitador" {{ $participante->cursos->find($curso->id)->pivot->rol == 'Facilitador' ? 'selected' : '' }}>Facilitador</option>
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-primary">Guardar Cambios</button>
            </form>

            <div id="error" style="color: red;"></div>
        </div>
    </div>
</x-layout>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('#tipo_documento, #numero_documento').forEach(function (input) {
            input.addEventListener('change', function () {
                let tipo_documento = document.getElementById('tipo_documento').value;
                let numero_documento = document.getElementById('numero_documento').value;
                let current_tipo_documento = "{{ $participante->tipo_documento }}";
                let current_numero_documento = "{{ $participante->numero_documento }}";

                let data = {
                    tipo_documento: tipo_documento,
                    numero_documento: numero_documento,
                    current_tipo_documento: current_tipo_documento,
                    current_numero_documento: current_numero_documento
                };

                fetch("{{ route('participante.verificarDocumento') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.existe) {
                        document.getElementById('error').innerText = 'El tipo y número de documento ya están ocupados.';
                    } else {
                        document.getElementById('error').innerText = '';
                    }
                });
            });
        });

        document.getElementById('modificarParticipanteForm').addEventListener('submit', function (e) {
            if (document.getElementById('error').innerText !== '') {
                e.preventDefault();
                alert('Por favor, corrija los errores antes de continuar.');
            }
        });
    });
</script>
