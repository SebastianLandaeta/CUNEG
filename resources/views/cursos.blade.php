<x-layout>
    @section('title', 'Cursos')

    <!-- Modal para la creacion de cursos -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        @component('components.modal')
            @slot('ButtonType', 'btn btn-primary me-2')
            @slot('ButtonText', 'Crear curso')
            @slot('ModalId', 'ModalForm')
            @slot('ModalLabel', 'ModalLabel')
            @slot('ModalTitle', 'Formulario de curso')

            <div class="modal-body">
                <form method="POST" action="{{ route('cursos') }}">
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

    <!-- Tabla con los cursos disponibles -->
    <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">Curso</th>
            <th scope="col">Fecha de inicio</th>
            <th scope="col">Fecha de finalización</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($cursos as $curso)
            <tr>
                <td>{{ $curso->nombre }}</td>
                <td>{{ $curso->f_inicio }}</td>
                <td>{{ $curso->f_finalizacion }}</td>
                <td>
                    <!-- Modal para la modificacion de cursos -->
                    @component('components.modal')
                        @slot('ButtonType', 'btn btn-warning btn-sm')
                        @slot('ButtonText', 'Modificar')
                        @slot('ModalId', 'ModalMod' .  $curso->id)
                        @slot('ModalLabel', 'ModalModLabel' . $curso->id)
                        @slot('ModalTitle', 'Modificación de curso')

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

                                <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                            </form>
                        </div>
                    @endcomponent

                    <!-- Modal para la eliminación de cursos -->
                    @component('components.modal')
                        @slot('ButtonType', 'btn btn-danger btn-sm')
                        @slot('ButtonText', 'Eliminar')
                        @slot('ModalId', 'ConfirmDelete' . $curso->id)
                        @slot('ModalLabel', 'ConfirmDeleteLabel' . $curso->id)
                        @slot('ModalTitle', 'Confirmar eliminación')

                        <div class="modal-body">
                            ¿Estás seguro de que deseas eliminar este curso?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    @endcomponent
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No se encontraron cursos.</td>
            </tr>
            @endforelse
        </tbody>
      </table>

      <!-- Enlaces de paginación -->
        <div class="d-flex justify-content-center">
            {{ $cursos->links() }}
        </div>
</x-layout>
