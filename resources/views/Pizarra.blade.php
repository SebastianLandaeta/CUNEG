<x-layout>
    @section('title', 'Cursos')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header text-center">Pizarra de Diseño "{{$curso->nombre}}"</div>

                <div class="card-body">

                    <!-- Barra de herramientas -->
                    <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <!--formas simples-->
                        <div class="btn-group me-2" role="group" aria-label="Insertar formas">
                            <button id="btnRectangulo" type="button" class="btn btn-primary">Rectángulo</button>
                            <button id="btnCirculo" type="button" class="btn btn-primary">Círculo</button>
                        </div>

                        <!-- Botón para insertar formas especiales (nombre del participante/cedula/correo/codigo qr) -->
                        <div class="btn-group me-2" role="group" aria-label="Insertar formas">
                            <button id="btnInsertarQR" type="button" class="btn btn-success">Código QR</button>
                            <button id="btnInsertarTexto" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#Insertar_texto">Texto</button>
                        </div>

                        <div class="btn-group me-2" role="group" aria-label="Insertar imágenes">
                            <button id="btnInsertarImagen" type="button" class="btn btn-warning">Insertar Imagen (PNG)</button>
                        </div>

                        <!--Modal para insertar texto-->
                        @component('components.modal')
                            @slot('ModalTitle', 'Insertar texto')
                            @slot('ModalId', 'Insertar_texto')
                            @slot('ModalLabel', 'Insertar_texto_label')
                            @slot('ModalSize', 'modal-dialog')
                            <div class="modal-body">
                                Los datos de los participantes van entre "<>" para ser reemplazados al momento de emitirse los certificados
                                ejemplo "&lt;nombre&gt;", "&lt;apellido&gt;" etc...
                                <br>
                                Elementos especiales: nombre, apellido, cedula, email.
                                <div class="m-3">
                                    <label for="text_input" class="form-label">Texto: </label>
                                    <input type="text" class="form-control" name="text_input" required>
                                </div>
                                <div class="m-3">
                                <label for="font_select" class="form-label">Fuente: </label>
                                    <select class="form-control" name="font_select">
                                        <option value="Arial" style="font-family: Arial;">Arial</option>
                                        <option value="Times New Roman" style="font-family: Times New Roman;">Times New Roman</option>
                                        <option value="Courier New" style="font-family: Courier New;">Courier New</option>
                                        <option value="Georgia" style="font-family: Georgia;">Georgia</option>
                                        <option value="Verdana" style="font-family: Verdana;">Verdana</option>
                                        <option value="Papyrus" style="font-family: Papyrus;">Papyrus</option>
                                        <option value="Broadway" style="font-family: Broadway;">Broadway</option>
                                    </select>
                                </div>
                                <div class="m-3">
                                <label for="font_size" class="form-label">Tamaño de fuente: </label>
                                    <input type="number" class="form-control" name="font_size" value="20" min="8" max="72" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                            <button id="btnGuardarTexto" type="button" class="btn btn-primary">Insertar texto</button>
                            </div>
                        @endcomponent

                        <!-- Eliminar objetos -->
                        <div class="btn-group me-2" role="group" aria-label="Eliminar forma">
                            <button id="btnEliminarForma" type="button" class="btn btn-danger">Eliminar Forma</button>
                        </div>
                    </div>

                    <!-- Pizarra de diseño con borde -->
                    <div id="canvas-container" class="d-flex align-items-center justify-content-center">
                        <canvas id="canvas" width="1000" height="650" style="border: 1px solid #000;" ></canvas> <!-- style="height: calc(800px / 1.29); width: 800px;-->
                    </div>

                    <!-- Formulario para guardar la imagen -->
                    <form id="formGuardarImagen" action="{{ route('pizarra.guardar', ['curso' => $curso]) }}" method="POST" >
                        @csrf <!-- Agregar el token CSRF -->

                        <input type="hidden" name="formasSimples" id="formasSimples">

                        <input type="hidden" name="formasEspeciales" id="formasEspeciales">

                        <input type="hidden" name="canvasDimensions" id="canvasDimensions">

                        <button type="button" id="btnGuardarImagen" class="btn btn-primary d-block mx-auto mt-3">Guardar Imagen</button>
                    </form>

                    <!-- Mensaje de confirmación de guardado -->
                    <div id="mensajeGuardado" style="display: none;" class="alert alert-success mt-3" role="alert">
                        La imagen ha sido guardada correctamente.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var canvas = new fabric.Canvas('canvas');

        // Obtener las dimensiones del canvas
        var canvasWidth = canvas.getWidth();
        var canvasHeight = canvas.getHeight();

        // Combinar el ancho y el alto del canvas en un objeto JSON
        var canvasDimensions = {
            width: canvasWidth,
            height: canvasHeight
        };

        // Convertir el objeto JSON a una cadena JSON
        var canvasDimensionsJSON = JSON.stringify(canvasDimensions);

        // Llenar el campo oculto con las dimensiones del canvas en formato JSON
        document.getElementById('canvasDimensions').value = canvasDimensionsJSON;

        // Agregar un rectángulo blanco al canvas (bloqueado)
        var fondoBlanco = new fabric.Rect({
            left: 0,
            top: 0,
            width: canvas.width,
            height: canvas.height,
            fill: 'white', // Color blanco
            selectable: false, // Bloquear la selección
            evented: false // Deshabilitar eventos
        });

        canvas.add(fondoBlanco);

        var selectedShape = null; // Forma seleccionada para dibujar

        // Función para insertar una forma al canvas
        function insertarForma(event){
            if ((selectedShape) && !canvas.getActiveObject()) {
                var forma = null;
                switch(selectedShape) {
                    case 'rectangulo':
                        forma = new fabric.Rect({
                            left: event.pointer.x,
                            top: event.pointer.y,
                            width: 100,
                            height: 100,
                            fill: 'red'
                        });
                        break;
                    case 'circulo':
                        forma = new fabric.Circle({
                            left: event.pointer.x,
                            top: event.pointer.y,
                            radius: 50,
                            fill: 'blue'
                        });
                        break;
                    case 'qr':
                        forma = new fabric.Rect({
                            type: 'qr',
                            left: event.pointer.x,
                            top: event.pointer.y,
                            width: 100,
                            height: 100,
                            fill: 'white',
                            stroke: 'blue',
                            strokeWidth: 3,
                            selectable: true,
                            hasControls: true, // Agregar controles de transformación
                            cornerStyle: 'rect',
                            cornerSize: 10,
                            lockRotation: true,
                            lockScalingFlip: true, // Bloquear inversión de escalado
                            lockSkewingX : true,
                            lockSkewingY : true,
                        });
                        // Ocultar los controles de los bordes laterales y superior e inferior
                        forma.setControlVisible('ml', false); // Ocultar control medio izquierdo
                        forma.setControlVisible('mt', false); // Ocultar control medio superior
                        forma.setControlVisible('mr', false); // Ocultar control medio derecho
                        forma.setControlVisible('mb', false); // Ocultar control medio inferior
                        break;
                    case 'nombre':
                        forma = new fabric.Text('Nombre', {
                            type: 'name',
                            left: event.pointer.x,
                            top: event.pointer.y,
                            fontSize: 20,
                            fill: 'black',
                            fontFamily: 'Arial',
                            lockScalingFlip: true, // Especifica el tipo de letra aquí
                        });
                }

                if (forma) {
                    canvas.add(forma);
                    canvas.renderAll();
                }
            }
        }

        // Agregar un event listener al canvas para escuchar el evento de clic
        canvas.on('mouse:down', function(event) {
            insertarForma(event);
        });

        // Agregar un event listener al botón de insertar código QR
        var btnInsertarQR = document.querySelector('#btnInsertarQR');
        btnInsertarQR.addEventListener('click', function() {
            selectedShape = 'qr';
        });

        // Agregar un event listener al botón de rectángulo
        var btnRectangulo = document.querySelector('#btnRectangulo');
        btnRectangulo.addEventListener('click', function() {
            selectedShape = 'rectangulo';
        });

        // Agregar un event listener al botón de círculo
        var btnCirculo = document.querySelector('#btnCirculo');
        btnCirculo.addEventListener('click', function() {
            selectedShape = 'circulo';
        });

        // Agregar un event listener al botón de eliminar forma
        var btnEliminarForma = document.querySelector('#btnEliminarForma');
        btnEliminarForma.addEventListener('click', function() {
            var activeObject = canvas.getActiveObject();
            if (activeObject) {
                canvas.remove(activeObject);
                canvas.renderAll();
            }
        });

        var btnGuardarTexto = document.getElementById('btnGuardarTexto');

        btnGuardarTexto.addEventListener('click', function() {
            // Obtener el texto ingresado por el usuario desde el modal
            var textoUsuario = document.querySelector('#Insertar_texto input[name="text_input"]').value;
            var fuenteUsuario = document.querySelector('#Insertar_texto select[name="font_select"]').value;
            var tamañoFuenteUsuario = document.querySelector('#Insertar_texto input[name="font_size"]').value;

            // Crear un objeto de texto en el lienzo
            var objetoTexto = new fabric.Text(textoUsuario, {
                type: 'text',
                left: 100, // Posición X inicial
                top: 100, // Posición Y inicial
                fontSize: parseInt(tamañoFuenteUsuario), // Tamaño de fuente
                fill: 'black', // Color de texto
                fontFamily: fuenteUsuario // Fuente seleccionada
            });

            // Agregar el objeto de texto al lienzo
            canvas.add(objetoTexto);

            // Cerrar el modal después de agregar el texto al lienzo
            var modal = bootstrap.Modal.getInstance(document.getElementById('Insertar_texto'));
            modal.hide();
        });

        // Agregar un event listener al botón de insertar imágenes
        var btnInsertarImagen = document.querySelector('#btnInsertarImagen');
        btnInsertarImagen.addEventListener('click', function() {
            // Abrir el selector de archivos para que el usuario elija una imagen
            var input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/png'; // Solo aceptar imágenes PNG
            input.onchange = function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();
                        reader.onload = function(event) {
                            var imgObj = new Image();
                            imgObj.src = event.target.result;
                            imgObj.onload = function() {
                                // Convertir la imagen a base64
                                var canvasTemp = document.createElement('canvas');
                                var context = canvasTemp.getContext('2d');
                                canvasTemp.width = imgObj.width;
                                canvasTemp.height = imgObj.height;
                                context.drawImage(imgObj, 0, 0);
                                var base64Data = canvasTemp.toDataURL('image/png');

                                // Crear un objeto de imagen en Fabric.js y agregarlo al lienzo
                                var fabricImg = new fabric.Image(imgObj, {
                                    type: 'imagen',
                                    left: 100, // Posición X inicial
                                    top: 100, // Posición Y inicial
                                    scaleX: 0.5, // Escala inicial
                                    scaleY: 0.5,
                                    base64: base64Data // Agregar el atributo base64 al objeto de imagen
                                });
                                canvas.add(fabricImg);
                                canvas.renderAll();
                            };
                        };
                        reader.readAsDataURL(file);
                }
            };
            input.click(); // Mostrar el selector de archivos
        });

        // Agregar un event listener al botón de guardar imagen
        var btnGuardarImagen = document.getElementById('btnGuardarImagen');
        btnGuardarImagen.addEventListener('click', function() {
            guardarImagen();
        });

        // Función para guardar la imagen
        function guardarImagen() {

            var formasEspeciales = [];
            var formasSimples = [];

            // Obtener las formas en el canvas
            canvas.getObjects().forEach(function(object) {
                switch (object.type) {
                    case 'rect':
                        formasSimples.push({
                            type: object.type,
                            left: object.left,
                            top: object.top,
                            width: object.width,
                            height: object.height,
                            fill: object.fill,
                            angle: object.angle,
                            scaleX: object.scaleX,
                            scaleY: object.scaleY
                        });
                        break;
                    case 'circle':
                        formasSimples.push({
                            type: object.type,
                            left: object.left,
                            top: object.top,
                            radius: object.radius,
                            scaleX: object.scaleX,
                            scaleY: object.scaleY,
                            angle: object.angle,
                            fill: object.fill
                        });
                        break;
                    case 'qr':
                        formasEspeciales.push({
                            type: object.type,
                            left: object.left,
                            top: object.top,
                            width: object.width * object.scaleX,
                            height: object.height * object.scaleY
                        });
                        break;
                    case 'name':
                        formasEspeciales.push({
                            type: 'name',
                            left: object.left,
                            top: object.top,
                            texto: object.text,
                            fontSize: object.fontSize,
                            fill: object.fill,
                            angle: object.angle
                        });
                        break;
                    case 'text':
                        formasEspeciales.push({
                            type: 'text',
                            left: object.left,
                            top: object.top,
                            texto: object.text,
                            fontSize: object.fontSize,
                            font: object.fontFamily,
                            fill: object.fill,
                            angle: object.angle
                        });
                        break;
                    case 'imagen':
                        formasEspeciales.push({
                            type: 'imagen',
                            left: object.left,
                            top: object.top,
                            scaleX: object.scaleX,
                            scaleY: object.scaleY,
                            base64: object.base64, // Incluir el atributo base64 en el JSON
                            angle: object.angle,
                            width: object.width, // Incluir la propiedad width
                            height: object.height // Incluir la propiedad height
                        });
                        break;
                    }
            });

            // Llenar los campos ocultos del formulario con los datos a enviar
            document.getElementById('formasSimples').value = JSON.stringify(formasSimples);
            document.getElementById('formasEspeciales').value = JSON.stringify(formasEspeciales);

            console.log(canvasDimensionsJSON);

            const jsonString = JSON.stringify(formasSimples, null, 4); // Usa 4 espacios para la sangría
            console.log(jsonString);

            const jsonString2 = JSON.stringify(formasEspeciales, null, 4); // Usa 4 espacios para la sangría
            console.log(jsonString2);

            //Enviar el formulario
            document.getElementById('formGuardarImagen').submit();
        }
    });
</script>
