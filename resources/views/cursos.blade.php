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
            <th scope="col" class="text-center">ID</th>
            <th scope="col" class="text-center">Nombre del curso</th>
            <th scope="col" class="text-center">Descripción</th>
            <th scope="col" class="text-center">Fecha de inicio</th>
            <th scope="col" class="text-center">Fecha de finalización</th>
            <th scope="col" class="text-center">Acciones sobre el curso</th>
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

                <td class="d-block">
                    <!-- Boton propiedades del curso-->
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

                        <!--Modal para la modificacion de curso-->
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

                <!-- Boton propiedades del listado de participantes-->
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

                <!--Modal para MOSTRAR lista de participantes-->
                @component('components.modal')
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

                <!--Modal para CARGAR lista de participantes-->
                @component('components.modal')
                    @if (!$curso->lista_cargada)
                        @slot('ModalTitle', 'Carga de listado de participantes')
                    @else
                        @slot('ModalTitle', 'Actualización de listado de participantes')
                    @endif
                        @slot('ModalId', 'ModalDeCarga' . $curso->id )
                        @slot('ModalLabel', 'ModalCargaLabel' . $curso->id )
                        @slot('ModalSize', 'modal-dialog')

                        <div class="modal-body">
                            <form action="{{ route('curso.loadedList', $curso) }}" method="POST" enctype="multipart/form-data">
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
                            <script src="{{ asset('js/excelForm.js') }}"></script>
                        </div>
                @endcomponent

                <!--Modal para ELIMINAR lista de participantes-->
                @component('components.modal')
                    @slot('ModalTitle', 'Eliminar listado de participantes')
                    @slot('ModalId', 'ModalDeEliminacionParticipantes' . $curso->id )
                    @slot('ModalLabel', 'ModalEliminacionParticipantesLabel' . $curso->id )
                    @slot('ModalSize', 'modal-dialog')
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar los participantes del curso: <b>"{{$curso->nombre}}"</b>?
                    </div>
                    <div class="modal-footer">
                    <!-- Formulario para eliminar el listado cargado -->
                        <form action="{{ route('curso.deleteList', $curso) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar Lista</button>
                        </form>
                    </div>
                @endcomponent

                    <!-- Boton propiedades del diseño del certificado del curso-->
                    <div class="p-2">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Diseño de certificado
                            </button>
                            <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{route('pizarra.index', ['curso' => $curso])}}" class="dropdown-item">
                                            Crear diseño nuevo
                                        </a>
                                    </li>
                                    @if($curso->certificado_cargado)
                                        <li>
                                            <a href="#" class="dropdown-item item_visualizacion"  data-id="{{ $curso->id }}" data-bs-toggle="modal" data-bs-target="#{{'Pizarra' . $curso->id}}">
                                                Ver diseño
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalDeEliminacionCertificado' . $curso->id}}">
                                                Eliminar diseño
                                            </a>
                                        </li>
                                    @endif
                            </ul>
                        </div>
                    </div>

                    <div class="p-2">
                        <a href="{{ route('emitir_certificados',['curso' => $curso]) }}"
                        class="{{$curso->certificado_cargado && $curso->lista_cargada? "btn btn-primary":"btn btn-primary disabled"}}"
                        type="button">
                            Emitir certificados
                        </a>
                    </div>
                </td>
            </tr>

            <!--Modal para VISUALIZAR diseño de certificado-->
            @component('components.modal')
                @slot('ModalTitle', 'Visualizacion de pizarra')
                @slot('ModalId', 'Pizarra' . $curso->id )
                @slot('ModalLabel', 'PizarraLabel' . $curso->id )
                @slot('ModalSize', 'modal-dialog modal-xl modal-dialog-centered')
                    <div class="container container_pizarra" style="overflow: auto; width: 100%; height: 80vh;">
                        <canvas id="previewCanvas_{{$curso->id}}" class="d-block mx-auto" width="800" height="600" style="border: 1px solid #ccc;"></canvas>
                    </div>
            @endcomponent

            <!--Modal para ELIMINAR diseño de certificado-->
            @component('components.modal')
                @slot('ModalTitle', 'Eliminar diseño de certificado')
                @slot('ModalId', 'ModalDeEliminacionCertificado' . $curso->id )
                @slot('ModalLabel', 'ModalEliminacionCertificadoLabel' . $curso->id )
                @slot('ModalSize', 'modal-dialog')
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar el diseño del certificado del curso: <b>"{{$curso->nombre}}"</b>?
                </div>
                <div class="modal-footer">
                <!-- Formulario para eliminar el listado cargado -->
                    <form action="{{ route('pizarra.eliminar', $curso) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar Lista</button>
                    </form>
                </div>
            @endcomponent

            @empty
                <tr>
                    <td colspan="5" class="text-center">No se encontraron cursos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

        <!-- Enlaces de paginación -->
        <div class="d-flex justify-content-center">
            {{ $cursos->links() }}
        </div>
</x-layout>

<script src="{{ asset('js/construir_pizarra_ejemplo.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const visualizarEnlaces = document.querySelectorAll('.item_visualizacion');
        // Event listener para los enlaces de visualización
        visualizarEnlaces.forEach(enlace => {
            enlace.addEventListener('click', function (event) {
                event.preventDefault();
                const idCurso = this.getAttribute('data-id');
                visualizarPizarra(idCurso);
            });
        });
    });

    function visualizarPizarra(idCurso) {
    fetch('{{ route("pizarra.visualizar") }}?idCurso=' + idCurso)
        .then(response => response.json())
        .then(data => {
            console.log('Datos de la pizarra:', data);
            dibujarObjetos(data, idCurso);
        })
        .catch(error => console.error('Error al obtener los datos de la pizarra:', error));
    }
</script>
