<x-layout>
    @section('title', 'Cursos')

    <!-- Modal para la creación de cursos -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        @component('components.modal')
            @slot('ButtonType', 'btn btn-primary me-2')
            @slot('ButtonText', 'Crear curso')
            @slot('ModalId', 'ModalForm')
            @slot('ModalLabel', 'ModalLabel')
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
            <th scope="col" class="text-center">Descripción</th>
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
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalMod'.$curso->id}}">
                                        Modificar curso
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ConfirmDelete' . $curso->id}}">
                                        Eliminar curso
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!--Modal para la modificación de curso-->
                    @component('components.modal')
                        @slot('ModalId', 'ModalMod' .  $curso->id)
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

                    <!-- Botón propiedades del listado de participantes-->
                    <div class="p-2">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Listado de participantes
                            </button>
                            <ul class="dropdown-menu">
                                @if (!$curso->lista_cargada)
                                    <li>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalDeCarga' . $curso->id}}">
                                            Cargar lista
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalDeListado' . $curso->id}}">
                                            Ver lista
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalDeCarga' . $curso->id}}">
                                            Actualizar lista
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalDeEliminacionParticipantes' . $curso->id}}">
                                            Eliminar lista
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay cursos disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layout>
