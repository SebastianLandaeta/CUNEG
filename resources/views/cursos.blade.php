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
            @slot('ModalSize', 'modal-dialog')
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

                    <!--Modal para cargar lista de participantes-->
                    @if ($curso->lista_cargada)
                    @component('components.modal')
                        @slot('ButtonType', 'btn btn-primary btn-sm')
                        @slot('ButtonText', 'Lista Cargada')
                        @slot('ModalTitle', 'Listado de participantes')
                        @slot('ModalId', 'ModalDeListado' . $curso->id)
                        @slot('ModalLabel', 'ModalListadoLabel' . $curso->id)
                        @slot('ModalSize', 'modal-dialog modal-xl')
                        <div class="modal-body">
                            <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td>Cedula</td>
                                                <td>Nombre</td>
                                                <td>Apellido</td>
                                                <td>Email</td>
                                                <td>Rol</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($curso->participantes as $participante)
                                            <tr>
                                                <td>{{ $participante->cedula }}</td>
                                                <td>{{ $participante->nombre }}</td>
                                                <td>{{ $participante->apellido }}</td>
                                                <td>{{ $participante->email }}</td>
                                                <td>
                                                {{ app('App\Helpers\FileProcessing\FileProcessing')->searchRol($curso,$participante) }}
                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                            <form action="{{ route('curso.ExtractList', $curso) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="clo-md-6 d-flex align-items-center justify-content-center">
                                    <button class="btn btn-outline-success">Extraer Lista</button>
                                </div>

                            </form>
                        </div>

                    @endcomponent
                @endif


                    @component('components.modal')
                        @if (!$curso->lista_cargada)
                            @slot('ButtonType', 'btn btn-info btn-sm')
                            @slot('ButtonText', 'Cargar Lista')
                            @slot('ModalTitle', 'Carga de listado de participantes')
                        @else
                            @slot('ButtonType', 'btn btn-success btn-sm')
                            @slot('ButtonText', 'Actualizar Lista')
                            @slot('ModalTitle', 'Actualizacion de listado de participantes')
                        @endif
                            @slot('ModalId', 'ModalDeCarga' . $curso->id )
                            @slot('ModalLabel', 'ModalCargaLabel' . $curso->id )
                            @slot('ModalSize', 'modal-dialog')


                            <div class="modal-body">
                                <form action="{{ route('curso.loadedList', $curso) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <p class="text-center pt-2"><b>A considerar: </b><p>
                                        <ul>
                                            <li>Verifique que su excel este con la estructura correcta.</li>
                                            <li>Para saber cual es la estructura correcta para cargar los participantes descargue el <b>Modelo Excel</b>.</li>
                                            <li>Al momento de llenar el listado no subraye ni altere nada de la tipografia por defecto del Excel.</li>
                                            <li>Si un participante es registrado nuevamente desde cualquier listado de cualquier curso, sus datos personales se actualizaran segun la nueva lista.</li>
                                        </ul>
                                    <div class="clo-md-6 pb-2">
                                        <input class="form-control" type="file" name="documento" accept=".xls, .xlsx">
                                    </div>

                                    <div class="clo-md-6 d-flex align-items-center justify-content-center">
                                        <a href="{{ route('d-excel') }}" class="btn btn-outline-secondary m-2">Modelo Excel</a>
                                        <button class="btn btn-outline-primary m-2" type="submit">Importar Lista</button>
                                    </div>

                                </form>
                            </div>
                    @endcomponent



                    <!-- Modal para la modificacion de cursos -->
                    @component('components.modal')
                        @slot('ButtonType', 'btn btn-warning btn-sm')
                        @slot('ButtonText', 'Modificar')
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
                        @slot('ModalSize', 'modal-dialog')

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
