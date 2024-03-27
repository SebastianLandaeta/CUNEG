<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canvas Modificado</title>
    <style>
        #canvas {
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <canvas id="canvas" width="800" height="600"></canvas>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');

            // Obtener los datos de la sesión
            var formasEspeciales = {!! json_encode($formasEspeciales) !!};
            var formasSimples = {!! json_encode($formasSimples) !!};
            var datosUsuario = {!! json_encode($datosUsuario) !!};

            // Función para dibujar los objetos en el canvas
            function dibujarObjetos() {
                formasSimples.forEach(function(objeto) {
                    if (objeto.type === 'rect') {
                        dibujarRectangulo(objeto);
                    } else if (objeto.type === 'circle') {
                        dibujarCirculo(objeto);
                    }
                });

                formasEspeciales.forEach(function(objeto) {
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

            // Llamar a la función para dibujar los objetos
            dibujarObjetos();
        });
    </script>
</body>
</html>
