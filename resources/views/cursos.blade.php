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
            <th scope="col" class="text-center">Nombre del Curso</th>
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
                    <td class="align-middle text-center">{{ $curso->f_inicio }}</td>
                    <td class="align-middle text-center">{{ $curso->f_finalizacion }}</td>
                

                <td class="d-block">
                    <!-- Boton propiedades del curso-->
                    <div class="p-2">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Propiedades del Curso
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalMod'.$curso->id}}">
                                        Modificar Curso
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
                            <li>
                                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalDeCarga' . $curso->id}}">
                                    @if (!$curso->lista_cargada)
                                        Cargar lista
                                    @else
                                        Actualizar lista
                                    @endif
                                </a>
                            </li>

                            @if ($curso->lista_cargada)
                                <li>
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#{{'ModalDeListado' . $curso->id}}">
                                        Lista cargada
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

                <!--Modal para ELIMINAR lista de participantes-->
                @component('components.modal')
                    @slot('ModalTitle', 'Eliminar listado de participantes')
                    @slot('ModalId', 'ModalDeEliminacionParticipantes' . $curso->id )
                    @slot('ModalLabel', 'ModalEliminacionParticipantesLabel' . $curso->id )
                    @slot('ModalSize', 'modal-dialog')
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar los participantes de este curso?
                    </div>
                    <div class="modal-footer">
                    <!-- Formulario para eliminar el listado cargado -->
                        <form action="{{ route('curso.deleteList', $curso) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar Lista</button>
                        </form>
                    </div>W
                    @endcomponent

                    <!-- Boton propiedades del diseño del certificado del curso-->
                    <div class="p-2">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Diseño de certificado
                            </button>
                            <ul class="dropdown-menu">
                                
                                    <li>
                                        <a href="{{route('pizarra.index', ['idCurso' => $curso->id])}}" class="dropdown-item">
                                            Crear/Actualizar diseño nuevo
                                        </a>
                                    </li>
                                    
                                    @if($curso->certificado_cargado)
                                        <li>
                                            <a href="#" class="dropdown-item item_visualizacion"  data-id="{{ $curso->id }}" data-bs-toggle="modal" data-bs-target="#{{'Pizarra' . $curso->id}}">
                                                Visualizar diseño
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#" class="dropdown-item">
                                                Eliminar diseño
                                            </a>
                                        </li>
                                    @endif
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            
            @component('components.modal')
                @slot('ModalTitle', 'Visualizacion de pizarra')
                @slot('ModalId', 'Pizarra' . $curso->id )
                @slot('ModalLabel', 'PizarraLabel' . $curso->id )
                @slot('ModalSize', 'modal-dialog modal-xl modal-dialog-centered')
                    <div class="container container_pizarra">
                        <canvas id="previewCanvas" class="d-block mx-auto" width="800" height="600" style="border: 1px solid #ccc;"></canvas>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('previewCanvas');
        const ctx = canvas.getContext('2d');

        function dibujarObjetos(data) {
            data.formasSimples.forEach(function(objeto) {
                if (objeto.type === 'rect') {
                    dibujarRectangulo(objeto);
                } else if (objeto.type === 'circle') {
                    dibujarCirculo(objeto);
                }
            });

            data.formasEspeciales.forEach(function(objeto) {
                if (objeto.type === 'name') {
                    dibujarTexto(objeto);
                } else if (objeto.type === 'qr') {
                    dibujarCodigoQR(objeto.qr_encode, objeto.left, objeto.top, objeto.width);
                }
            });
        }

        function dibujarRectangulo(objeto) {
            ctx.save(); // Guardar el estado actual del contexto
            ctx.translate(objeto.left, objeto.top); // Mover el origen al punto de partida del rectángulo
            ctx.rotate((objeto.angle * Math.PI) / 180); // Rotar el contexto según el ángulo de inclinación
            ctx.fillStyle = objeto.fill;
            ctx.fillRect(objeto.width * objeto.scaleX /180, objeto.height * objeto.scaleY /180, objeto.width * objeto.scaleX, objeto.height * objeto.scaleY); // Dibujar el rectángulo desde el punto de partida real
            ctx.restore(); // Restaurar el estado del contexto
        }

        function dibujarCirculo(objeto) {
            ctx.save(); // Guardar el estado actual del contexto
                if (objeto.scaleX === objeto.scaleY && objeto.angle === 0) {
                    // Si la escala en X es igual a la escala en Y y no hay rotación, dibujar un círculo perfecto con ctx.arc
                    ctx.beginPath();
                    ctx.fillStyle = objeto.fill;
                    ctx.arc(objeto.left + objeto.radius * objeto.scaleX, objeto.top + objeto.radius * objeto.scaleY, objeto.radius * objeto.scaleX, 0, 2 * Math.PI); // Dibujar el círculo desde el punto de partida (0, 0)
                    ctx.fill(); // Rellenar el círculo
                } else {
                    ctx.translate(objeto.left, objeto.top); // Mover el origen al centro del círculo
                    ctx.rotate((objeto.angle * Math.PI) / 180); // Rotar el contexto según el ángulo de inclinación
                    // Dibujar una elipse con ctx.ellipse
                    ctx.beginPath();
                    ctx.fillStyle = objeto.fill;
                    ctx.ellipse(((objeto.radius * objeto.scaleX + 0.18)),((objeto.radius * objeto.scaleY + 0.18 )), objeto.radius * objeto.scaleX, objeto.radius * objeto.scaleY, 0, 0, 2*Math.PI);
                    ctx.fill(); // Rellenar la elipse
                }

                ctx.restore(); // Restaurar el estado del contexto
            }

            // Función para dibujar texto
            function dibujarTexto(objeto) {
                ctx.save(); // Guardar el estado actual del contexto

                // Mover el origen al punto de inicio del texto
                ctx.translate(objeto.left, objeto.top);

                // Rotar el contexto según el ángulo de inclinación
                ctx.rotate((objeto.angle * Math.PI) / 180);

                // Configurar el estilo de texto
                ctx.fillStyle = objeto.fill;
                ctx.font = objeto.fontSize + "px Arial"; // Establecer el tamaño y el tipo de fuente

                // Alinear el texto según el centro
                //ctx.textAlign = "center";
                //ctx.textBaseline = "middle";

                // Dibujar el texto en el punto de origen (0, 0)
                ctx.fillText(objeto.texto, 0, 0+objeto.fontSize);

                ctx.restore(); // Restaurar el estado del contexto
            }

            // Función para dibujar un código QR
            function dibujarCodigoQR(src, left, top, size) {
                var qr_encode = "data:image/png;base64," + src;
                var img = new Image();
                img.onload = function() {
                    ctx.drawImage(img, left, top, size, size);
                };
                img.src = qr_encode;
            }

    function visualizarPizarra(idCurso) {
        fetch('{{ route("pizarra.visualizar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF si estás utilizando Laravel
            },
            body: JSON.stringify({ idCurso: idCurso }) // Envía el ID del curso como parte del cuerpo de la solicitud
        })
        .then(response => response.json())
        .then(data => {
            // Imprimir los datos recibidos en la consola del navegador
            console.log('Datos de la pizarra:', data);
            dibujarObjetos(data);
        })
        .catch(error => console.error('Error al obtener los datos de la pizarra:', error));
    }

    const visualizarEnlaces = document.querySelectorAll('.item_visualizacion');
    
    // Iterar sobre cada elemento encontrado
    visualizarEnlaces.forEach(enlace => {
        // Agregar un event listener para el evento 'click'
        enlace.addEventListener('click', function(event) {
            // Evitar que el enlace realice la acción por defecto (navegación)
            event.preventDefault();
            // Obtener el ID del curso desde el atributo data-id
            const idCurso = this.getAttribute('data-id');
            // Hacer la petición para visualizar la pizarra
            visualizarPizarra(idCurso);
        });
    });

});
</script>
