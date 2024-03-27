<x-layout>
    @section('title', 'Cursos')

    <style>
        
    </style>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">Pizarra de Diseño</div>

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
                            <button id="btnInsertarNombre" type="button" class="btn btn-success">Nombre</button>
                            <button id="btnInsertarQR" type="button" class="btn btn-success">Código QR</button>
                        </div>

                        <!-- Eliminar objetos -->
                        <div class="btn-group me-2" role="group" aria-label="Eliminar forma">
                            <button id="btnEliminarForma" type="button" class="btn btn-danger">Eliminar Forma</button>
                        </div>
                    </div>

                    <!-- Pizarra de diseño con borde -->
                    <div id="canvas-container">
                        <canvas id="canvas" width="800" height="600" style="border: 1px solid #000;" ></canvas> <!-- style="height: calc(800px / 1.29); width: 800px;-->
                    </div>

                    <!-- Formulario para guardar la imagen -->
                    <form id="formGuardarImagen" action="{{ route('pizarra.guardar', ['idCurso' => $idCurso]) }}" method="POST" ><!--//target="_blank" PARA ABRIR EN VENTANA NUEVA Y PROBAR SI LA PIZARRA CARGO-->
                        @csrf <!-- Agregar el token CSRF -->
                        
                        <input type="hidden" name="formasSimples" id="formasSimples">
                        
                        <input type="hidden" name="formasEspeciales" id="formasEspeciales">
                        
                        <button type="button" id="btnGuardarImagen" class="btn btn-primary">Guardar Imagen</button>
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
                            qr: true
                        });
                        // Ocultar los controles de los bordes laterales y superior e inferior
                        forma.setControlVisible('ml', false); // Ocultar control medio izquierdo
                        forma.setControlVisible('mt', false); // Ocultar control medio superior
                        forma.setControlVisible('mr', false); // Ocultar control medio derecho
                        forma.setControlVisible('mb', false); // Ocultar control medio inferior
                        break;
                    case 'nombre':
                        forma = new fabric.Text('Nombre', {
                            left: event.pointer.x,
                            top: event.pointer.y,
                            fontSize: 20,
                            fill: 'black',
                            fontFamily: 'Arial',
                            lockScalingFlip: true, // Especifica el tipo de letra aquí
                            name: true
                        });
                    default:
                        console.log("Opción no válida");
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

        // Agregar un event listener al botón de insertar nombre
        var btnInsertarNombre = document.querySelector('#btnInsertarNombre');
        btnInsertarNombre.addEventListener('click', function() {
            selectedShape = 'nombre';
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
                if (object.qr){
                    formasEspeciales.push({
                        type: 'qr',
                        left: object.left,
                        top: object.top,
                        width: object.width * object.scaleX,
                        height: object.height * object.scaleY,
                    });
                }
                else if (object.type=='rect'){
                    formasSimples.push({
                        type: object.type,
                        left: object.left,
                        top: object.top,
                        width: object.width ,
                        height: object.height ,
                        fill: object.fill,
                        angle: object.angle,
                        scaleX: object.scaleX,
                        scaleY: object.scaleY
                    });
                }else if (object.type === 'circle') {
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
                } else if (object.name) {
                    formasEspeciales.push({
                        type: 'name',
                        left: object.left,
                        top: object.top,
                        texto: object.text,
                        fontSize: object.fontSize,
                        fill: object.fill,
                        angle: object.angle
                    });
                }
            });

            // Llenar los campos ocultos del formulario con los datos a enviar
            document.getElementById('formasSimples').value = JSON.stringify(formasSimples);
            document.getElementById('formasEspeciales').value = JSON.stringify(formasEspeciales);

            const jsonString = JSON.stringify(formasSimples, null, 4); // Usa 4 espacios para la sangría
            console.log(jsonString);

            const jsonString2 = JSON.stringify(formasEspeciales, null, 4); // Usa 4 espacios para la sangría
            console.log(jsonString2);

            // Enviar el formulario
            document.getElementById('formGuardarImagen').submit();
        }
    });
</script>