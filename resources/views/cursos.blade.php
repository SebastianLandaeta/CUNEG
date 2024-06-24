<x-layout>
    @section('title', 'Cursos')

    <!-- Modal para la creación de cursos -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#ModalCreacionCurso">Crear curso</button>

        <!--Modal para la CREACION de cursos-->
        @component('components.modal')
            @slot('ModalId', 'ModalCreacionCurso')
            @slot('ModalLabel', 'ModalCreacionCursoLabel')
            @slot('ModalTitle', 'Formulario de curso')
            @slot('ModalSize', 'modal-dialog')
            <div class="modal-body">
                <form method="POST" action="{{ route('cursos.create') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" class="form-control" name="descripcion" required>
                    </div>
                    <div class="mb-3">
                        <label for="f_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control" name="f_inicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="f_finalizacion" class="form-label">Fecha de finalización</label>
                        <input type="date" class="form-control" name="f_finalizacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="horas_academicas" class="form-label">Horas académicas</label>
                        <input type="number" class="form-control" name="horas_academicas" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                </form>
            </div>
        @endcomponent

        <!-- Buscador -->
        <form action="{{ route('cursos.search') }}" method="GET" class="flex-grow-1 me-2 d-flex align-items-center">
            <input type="text" class="form-control me-2" name="buscar" placeholder="Buscar por nombre">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
    </div>

    <!-- Mensaje de error -->
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    <!-- Tabla con los cursos disponibles -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col" class="text-center">ID</th>
                <th scope="col" class="text-center">Nombre del curso</th>
                <th scope="col" class="text-center">Descripción del curso</th>
                <th scope="col" class="text-center">Fecha de inicio</th>
                <th scope="col" class="text-center">Fecha de finalización</th>
                <th scope="col" class="text-center">Horas académicas</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursos as $curso)
                <tr>
                    <td class="align-middle text-center">{{ $curso->id }}</td>
                    <td class="align-middle text-center">{{ $curso->nombre }}</td>
                    <td class="align-middle text-center">{{ $curso->descripcion }}</td>
                    <td class="align-middle text-center">{{ $curso->f_inicio }}</td>
                    <td class="align-middle text-center">{{ $curso->f_finalizacion }}</td>
                    <td class="align-middle text-center">{{ $curso->horas_academicas }}</td>
                    <td class="d-block">
                        <!-- Botón propiedades del curso-->
                        <div class="p-2">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Propiedades del curso
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ModalMod{{ $curso->id }}">
                                            Modificar curso
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ConfirmDelete{{ $curso->id }}">
                                            Eliminar curso
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Modal para la modificación de curso-->
                        @component('components.modal')
                            @slot('ModalId', 'ModalMod' . $curso->id)
                            @slot('ModalLabel', 'ModalModLabel' . $curso->id)
                            @slot('ModalTitle', 'Modificación de curso')
                            @slot('ModalSize', 'modal-dialog')
                            <div class="modal-body">
                                <form action="{{ route('cursos.update', $curso) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" value="{{ $curso->nombre }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <input type="text" class="form-control" name="descripcion" value="{{ $curso->descripcion }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="f_inicio" class="form-label">Fecha de inicio</label>
                                        <input type="date" class="form-control" name="f_inicio" value="{{ $curso->f_inicio }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="f_finalizacion" class="form-label">Fecha de finalización</label>
                                        <input type="date" class="form-control" name="f_finalizacion" value="{{ $curso->f_finalizacion }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="horas_academicas" class="form-label">Horas académicas</label>
                                        <input type="number" class="form-control" name="horas_academicas" value="{{ $curso->horas_academicas }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                                </form>
                            </div>
                        @endcomponent

                        <!-- Modal para la eliminación de cursos -->
                        @component('components.modal')
                            @slot('ModalId', 'ConfirmDelete' . $curso->id)
                            @slot('ModalLabel', 'ConfirmDeleteLabel' . $curso->id)
                            @slot('ModalTitle', 'Confirmar eliminación')
                            @slot('ModalSize', 'modal-dialog')
                            <div class="modal-body">
                                ¿Estás seguro de que deseas eliminar este curso?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        @endcomponent

                        <!-- Modal para MOSTRAR lista de participantes -->
                        @component('components.modal')
                            @slot('ModalTitle', 'Listado de participantes de ' . $curso->nombre)
                            @slot('ModalId', 'ModalDeListado' . $curso->id)
                            @slot('ModalLabel', 'ModalListadoLabel' . $curso->id)
                            @slot('ModalSize', 'modal-dialog modal-xl')
                            <div class="modal-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <td>T. documento</td>
                                            <td>Nro. documento</td>
                                            <td>Nombre</td>
                                            <td>Apellido</td>
                                            <td>Email</td>
                                            <td>Rol</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($curso->participantes as $participante)
                                            <tr>
                                                <td>{{ $participante->tipo_documento }}</td>
                                                <td>{{ $participante->numero_documento }}</td>
                                                <td>{{ $participante->nombre }}</td>
                                                <td>{{ $participante->apellido }}</td>
                                                <td>{{ $participante->email }}</td>
                                                <td>{{$participante->pivot->rol}}</td>
                                                <td>
                                                    <div>
                                                        <a href="{{ route('participante.modificar', ['participante' => $participante]) }}" class="btn btn-warning">
                                                            Modificar Datos
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AgregarParticipanteModal">
                                    Agregar un participante 
                                </button>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalDeCarga{{ $curso->id }}">
                                    Agregar listado de participantes
                                </button>
                            </div>
                        @endcomponent

                        <!-- Modal para AGREGAR un participante -->
                        @component('components.modal')
                        @slot('ModalTitle', 'Agregar Participante al Curso ' . $curso->nombre)
                        @slot('ModalId', 'AgregarParticipanteModal')
                        @slot('ModalLabel', 'AgregarParticipanteModalLabel')
                        @slot('ModalSize', 'modal-dialog')
                        <div class="modal-body">
                            <form id="formAgregarParticipante" method="POST" action="{{ route('cursos.addParticipante', $curso->id) }}">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                    <input type="text" class="form-control @error('tipo_documento') is-invalid @enderror" id="tipo_documento" name="tipo_documento" value="{{ old('tipo_documento') }}" required>
                                    @error('tipo_documento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="numero_documento" class="form-label">Número de Documento</label>
                                    <input type="text" class="form-control @error('numero_documento') is-invalid @enderror" id="numero_documento" name="numero_documento" value="{{ old('numero_documento') }}" required>
                                    @error('numero_documento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                                    @error('apellido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="rol" class="form-label">Rol</label>
                                    <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                                        <option value="">Seleccionar Rol</option>
                                        <option value="Participante" @if (old('rol') == 'Participante') selected @endif>Participante</option>
                                        <option value="Instructor" @if (old('rol') == 'Instructor') selected @endif>Instructor</option>
                                        <option value="Facilitador" @if (old('rol') == 'Facilitador') selected @endif>Facilitador</option>
                                    </select>
                                    @error('rol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Agregar Participante</button>
                            </form>
                        </div>
                    @endcomponent

                        
                        <!--Modal para eliminar participantes-->
                        @component('components.modal')
                            @slot('ModalTitle', 'Eliminar participantes de ' . $curso->nombre)
                            @slot('ModalId', 'ModalDeEliminacionParticipantes' . $curso->id)
                            @slot('ModalLabel', 'ModalDeEliminacionParticipantesLabel' . $curso->id)
                            @slot('ModalSize', 'modal-dialog modal-xl')
                            <div class="modal-body">
                                <form action="{{ route('curso.deleteSelectedParticipants', $curso->id) }}" method="POST" id="deleteParticipantsForm{{$curso->id}}">
                                    @csrf
                                    @method('DELETE')
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="marcarTodos" onclick="checkAllParticipants('deleteParticipantsForm{{$curso->id}}')">Seleccionar todos</button>
                                                </td>
                                                <td>T. documento</td>
                                                <td>Nro. documento</td>
                                                <td>Nombre</td>
                                                <td>Apellido</td>
                                                <td>Email</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($curso->participantes as $participante)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="participantes[]" class="form-check-input" value="{{ $participante->id }}">
                                                    </td>
                                                    <td>{{ $participante->tipo_documento }}</td>
                                                    <td>{{ $participante->numero_documento }}</td>
                                                    <td>{{ $participante->nombre }}</td>
                                                    <td>{{ $participante->apellido }}</td>
                                                    <td>{{ $participante->email }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="container d-flex justify-content-center">
                                        <button type="submit" class="btn btn-danger">Eliminar seleccionados</button>
                                    </div>
                                </form>
                            </div>
                        @endcomponent


                        <!-- Botón propiedades del listado de participantes-->
                        <div class="p-2">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Listado de participantes
                                </button>
                                <ul class="dropdown-menu">
                                    @if ($curso->cursoParticipantes->isEmpty())
                                        <li>
                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ModalDeCarga{{ $curso->id }}">
                                                Cargar lista
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ModalDeListado{{ $curso->id }}">
                                                Ver listado / Modificar o agregar Participante
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ModalDeEliminacionParticipantes{{ $curso->id }}">
                                                Eliminar participantes
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Modal de carga de participantes -->
                @component('components.modal')
                    @if ($curso->cursoParticipantes->isEmpty())
                        @slot('ModalTitle', 'Carga de listado de participantes')
                    @else
                        @slot('ModalTitle', 'Actualización de listado de participantes')
                    @endif
                    @slot('ModalId', 'ModalDeCarga' . $curso->id)
                    @slot('ModalLabel', 'ModalCargaLabel' . $curso->id)
                    @slot('ModalSize', 'modal-dialog')

                    <div class="modal-body">
                        <form action="{{ route('curso.loadList', $curso) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <p class="text-center pt-2"><b>A considerar: </b><p>
                            <ul>
                                <li>Verifique que su Excel tenga la estructura correcta.</li>
                                <li>Para saber cual es la estructura correcta para cargar los participantes descargue el <b><a id="excelLink" href="{{ route('d-excel') }}">Modelo Excel</a></b>.</li>
                                <li>Al momento de llenar el listado no subraye ni altere nada de la tipografía por defecto del Excel.</li>
                                <li>Si un participante es registrado nuevamente desde cualquier listado de cualquier curso, sus datos personales se actualizarán según la nueva lista.</li>
                            </ul>
                            <div class="clo-md-6 pb-2">
                                <input class="form-control" type="file" name="documento" accept=".xls, .xlsx">
                            </div>

                            <div class="clo-md-6 d-flex align-items-center justify-content-center">
                                <button class="btn btn-outline-primary m-2" type="submit">Importar Lista</button>
                            </div>
                        </form>
                    </div>
                @endcomponent
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay cursos disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layout>

<script>
    function checkAllParticipants(formId) {
        let checkboxes = document.querySelectorAll(`#${formId} input[type='checkbox']`);
        checkboxes.forEach((checkbox) => {
            checkbox.checked = true;
        });
    }
</script>

