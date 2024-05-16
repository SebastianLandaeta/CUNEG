<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        #canvas {
            display: none; /* Ocultar el lienzo para que no sea visible en el navegador */
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>

<body style="margin: 0;">
    <canvas id="canvas" ></canvas>

<script>
        var correosEnviados=0;
        document.addEventListener("DOMContentLoaded", function() {
            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');

            var listaParticipantes = {!! json_encode($curso->participantes) !!};
            var cursoId = {!!  $curso->id !!}
            var curso = {!! $curso !!}


            canvas.width =  {!! $data->dimensionesCanvas->width !!};
            canvas.height = {!! $data->dimensionesCanvas->height !!};

            // Función para dibujar los objetos en el canvas
            async function dibujarCertificados() {
                // Iterar sobre la lista de participantes
                for (var i = 0; i < listaParticipantes.length; i++) {
                    var datosUsuario = listaParticipantes[i];
                    await dibujarCertificado(datosUsuario); // Dibujar el certificado para el participante actual
                }
            }

            async function dibujarCertificado(datosUsuario) {
                // Limpiar el lienzo antes de dibujar el nuevo certificado
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Obtener los datos de las formas especiales del certificado
                var formasEspeciales = {!! json_encode($data->formasEspeciales) !!};
                var formasSimples = {!! json_encode($data->formasSimples) !!};

                // Dibujar las formas simples
                formasSimples.forEach(function(objeto) {
                    switch(objeto.type) {
                        case 'rect':
                            dibujarRectangulo(objeto);
                            break;
                        case 'circle':
                            dibujarCirculo(objeto);
                            break;
                        default:
                            // En caso de que el tipo de forma no coincida con 'rect' o 'circle', puedes manejarlo aquí
                            mostrarMensajeError('Tipo de forma Simple no válido:' + objeto.type);
                            break;
                    }
                });


                // Dibujar las formas especiales (texto, código QR, etc.)
                await Promise.all(formasEspeciales.map(async (objeto) => {
                    switch(objeto.type) {
                        case 'imagen':
                            dibujarImagenPNG(objeto);
                            break;
                        case 'text':
                            var texto_procesado = objeto.texto;
                            texto_procesado = texto_procesado.replace("<nombre>", datosUsuario.nombre)
                                                            .replace("<cedula>", datosUsuario.cedula)
                                                            .replace("<apellido>", datosUsuario.apellido)
                                                            .replace("<email>", datosUsuario.email);
                            dibujarTexto(objeto, texto_procesado);
                            break;
                        case 'qr':
                            await dibujarCodigoQR(objeto.left, objeto.top, objeto.width, cursoId ,datosUsuario.cedula);
                            break;
                        default:
                            // En caso de que el tipo de objeto no coincida con 'imagen', 'text' o 'qr', puedes manejarlo aquí
                            mostrarMensajeError('Tipo de forma Especial no válido:' + objeto.type);
                            break;
                    }
                }));


                var imageData = ctx.getImageData(5, 3, canvas.width + 100, canvas.height + 100);

                // Crear un nuevo canvas para guardar la imagen recortada
                var newCanvas = document.createElement('canvas');
                var newCtx = newCanvas.getContext('2d');
                newCanvas.width = canvas.width-5;
                newCanvas.height = canvas.height-3;
                newCtx.putImageData(imageData, 0, 0);

                // Obtener la URL de datos de la imagen del nuevo canvas
                var dataURL = newCanvas.toDataURL('image/png');

                guardarCertificado(dataURL, datosUsuario.cedula, cursoId);
                mostrarMensajeExito(datosUsuario.nombre);
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

            function dibujarTexto(objeto, texto) {
                ctx.save(); // Guardar el estado actual del contexto

                // Mover el origen al punto de inicio del texto
                ctx.translate(objeto.left, objeto.top);

                // Rotar el contexto según el ángulo de inclinación
                ctx.rotate((objeto.angle * Math.PI) / 180);

                // Configurar el estilo de texto
                ctx.fillStyle = objeto.fill;
                ctx.font = objeto.fontSize + "px " + objeto.font; // Establecer el tamaño y el tipo de fuente

                // Alinear el texto según el centro
                //ctx.textAlign = "center";
                //ctx.textBaseline = "middle";

                // Dibujar el texto en el punto de origen (0, 0)
                ctx.fillText(texto, 0, 0+objeto.fontSize);

                ctx.restore(); // Restaurar el estado del contexto
            }

            function dibujarCodigoQR(left, top, size, idCurso, cedula) {
                return new Promise((resolve, reject) => {
                    fetch('{{ route("qr.participant") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            idCurso: idCurso,
                            cedula: cedula,
                            anchoQR: size
                        })
                    })
                    .then(response => response.text())
                    .then(qr_encode => {
                        var img = new Image();
                        img.onload = function() {
                            ctx.drawImage(img, left, top, size, size);
                            resolve(); // Resolver la promesa cuando la imagen se haya cargado correctamente
                        };
                        img.onerror = function() {
                            mostrarMensajeError('Error al fetch de dibujarCodigoQR');
                            reject(); // Rechazar la promesa si hay un error al cargar la imagen
                        };
                        img.src = "data:image/svg+xml;base64," + btoa(qr_encode);
                    })
                    .catch(error => {
                        mostrarMensajeError('Error al fetch de dibujarCodigoQR: ' + error); // Rechazar la promesa si hay un error en la solicitud fetch
                    });
                });
            }

            function dibujarImagenPNG(objeto) {
                ctx.save();
                var img = new Image();
                img.onload = function () {
                    ctx.save();
                    ctx.translate(objeto.left,objeto.top); // Establecer el origen en el centro de la imagen
                    ctx.rotate((objeto.angle * Math.PI) / 180); // Rotar en radianes
                    ctx.drawImage(img, objeto.width*objeto.scaleX/180 ,objeto.height*objeto.scaleY/180,  objeto.width*objeto.scaleX,objeto.height*objeto.scaleY); // Dibujar la imagen desde el centro
                    ctx.restore();
                };
                img.src = objeto.base64;
                ctx.restore();
            }

            function guardarCertificado(dataURL, cedulaParticipante, idCurso) {
                // Función para guardar el certificado en el servidor
                var token = '{{ csrf_token() }}';

                fetch('{{ route("emitir_certificados.guardado")}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        image: dataURL,
                        cedulaParticipante: cedulaParticipante,
                        idCurso: idCurso
                    })
                })
                .then(response => {
                    // Manejar la respuesta del servidor si es necesario
                })
                .catch(error => {
                    mostrarMensajeError('Error al fetch de guardarCertificado ' + error);
                });
            }

            async function enviarCorreo(participante , curso) {
                var token = '{{ csrf_token() }}';
                fetch('{{ route("correo_envio")}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        participante: participante,
                        curso: curso,
                    })
                })
                .then(() => {
                    //
                })
                .catch(error => {
                    mostrarMensajeError('Error al fetch de enviarCorreo ' + error);
                });
            }

            async function enviarCorreosATodos(){
                for (let i = 0; i < listaParticipantes.length; i++) {
                    await enviarCorreo(listaParticipantes[i], curso);
                }
                mostrarMensajeExitoYCerrarVentana();
            }

            // Llamar a la función para dibujar los certificados
            dibujarCertificados()
                .then(() => {
                    enviarCorreosATodos();
                })
                .catch(error => {
                    mostrarMensajeError('Error al fetch de dibujarCertificados ' + error);
                });
    });

    function mostrarMensajeExitoYCerrarVentana() {
        finalProcesoBueno();
        setTimeout(function() {
            window.close();
        }, 5000); // Cerrar la ventana después de 5 segundos
    }

    function mostrarMensajeExito(nombreUsuario) {
        var mensaje = "Certificado de " + nombreUsuario + " generado con éxito";
        // Crear el elemento de alerta de Bootstrap
        var alertaElement = document.createElement('div');
        alertaElement.classList.add('alert', 'alert-primary'); // Agregar las clases de Bootstrap para el estilo de alerta
        alertaElement.setAttribute('role', 'alert');
        alertaElement.textContent = mensaje;

        // Agregar el mensaje de éxito al cuerpo del documento
        document.body.appendChild(alertaElement);
    }


    function finalProcesoBueno() {
        // Crear el elemento de alerta de Bootstrap
        var alertaElement = document.createElement('div');
        alertaElement.classList.add('alert', 'alert-success'); // Agregar las clases de Bootstrap para el estilo de alerta
        alertaElement.setAttribute('role', 'alert');
        alertaElement.textContent = "Todos los certificados han sido generados, guardados y enviados \n. Cerrando ventana.";
        // Agregar el mensaje de éxito al cuerpo del documento
        document.body.appendChild(alertaElement);
    }

    function mostrarMensajeError(errorMsg) {
        // Crear el elemento de alerta de Bootstrap para el error
        var alertaElement = document.createElement('div');
        alertaElement.classList.add('alert', 'alert-danger'); // Agregar las clases de Bootstrap para el estilo de alerta de error
        alertaElement.setAttribute('role', 'alert');
        alertaElement.textContent = "Error: " + errorMsg;
        // Agregar el mensaje de error al cuerpo del documento
        document.body.appendChild(alertaElement);
    }
    </script>
</body>
</html>
