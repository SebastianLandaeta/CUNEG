// Función para dibujar objetos en el canvas
function dibujarObjetos(data, idCurso, ctx) {
    const canvas = document.getElementById('previewCanvas_' + idCurso);
    ctx = ctx || canvas.getContext('2d'); // Si no se proporciona el contexto, obtenerlo del canvas

    canvas.width = data.dimensionesCanvas.width;
    canvas.height = data.dimensionesCanvas.height;

    data.formasSimples.forEach(objeto => {
        switch (objeto.type) {
            case 'rect':
                dibujarRectangulo(ctx, objeto);
                break;
            case 'circle':
                dibujarCirculo(ctx, objeto);
                break;
        }
    });

    data.formasEspeciales.forEach(objeto => {
        switch (objeto.type) {
            case 'text':
                dibujarTexto(ctx, objeto);
                break;
            case 'qr':
                dibujarCodigoQR(ctx, objeto.qr_encode, objeto.left, objeto.top, objeto.width);
                break;
            case 'imagen':
                dibujarImagenPNG(ctx, objeto.base64, objeto.left, objeto.top, objeto.width, objeto.scaleX, objeto.height , objeto.scaleY, objeto.angle);
                break;
        }
    });
}

// Función para dibujar un rectángulo
function dibujarRectangulo(ctx, objeto) {
    ctx.save();
    ctx.translate(objeto.left, objeto.top);
    ctx.rotate((objeto.angle * Math.PI) / 180);
    ctx.fillStyle = objeto.fill;
    ctx.fillRect(objeto.width * objeto.scaleX / 180, objeto.height * objeto.scaleY / 180, objeto.width * objeto.scaleX, objeto.height * objeto.scaleY);
    ctx.restore();
}

// Función para dibujar un círculo
function dibujarCirculo(ctx, objeto) {
    ctx.save();
    if (objeto.scaleX === objeto.scaleY && objeto.angle === 0) {
        ctx.beginPath();
        ctx.fillStyle = objeto.fill;
        ctx.arc(objeto.left + objeto.radius * objeto.scaleX, objeto.top + objeto.radius * objeto.scaleY, objeto.radius * objeto.scaleX, 0, 2 * Math.PI);
        ctx.fill();
    } else {
        ctx.translate(objeto.left, objeto.top);
        ctx.rotate((objeto.angle * Math.PI) / 180);
        ctx.beginPath();
        ctx.fillStyle = objeto.fill;
        ctx.ellipse(((objeto.radius * objeto.scaleX + 0.18)), ((objeto.radius * objeto.scaleY + 0.18)), objeto.radius * objeto.scaleX, objeto.radius * objeto.scaleY, 0, 0, 2 * Math.PI);
        ctx.fill();
    }
    ctx.restore();
}

// Función para dibujar texto
function dibujarTexto(ctx, objeto) {
    ctx.save();
    ctx.translate(objeto.left, objeto.top);
    ctx.rotate((objeto.angle * Math.PI) / 180);
    ctx.fillStyle = objeto.fill;
    ctx.font = objeto.fontSize + "px Arial";
    ctx.fillText(objeto.texto, 0, 0 + objeto.fontSize);
    ctx.restore();
}

// Función para dibujar un código QR
function dibujarCodigoQR(ctx, src, left, top, size) {
    ctx.save();
    var qr_encode = "data:image/png;base64," + src;
    var img = new Image();
    img.onload = function () {
        ctx.drawImage(img, left, top, size, size);
    };
    img.src = qr_encode;
    ctx.restore();
}

function dibujarImagenPNG(ctx, src, left, top, width, scaleX,  height, scaleY, angle) {
    ctx.save();
    var img = new Image();
    img.onload = function () {
        ctx.save();
        ctx.translate(left,top); // Establecer el origen en el centro de la imagen
        ctx.rotate((angle * Math.PI) / 180); // Rotar en radianes
        ctx.drawImage(img, width*scaleX/180 ,height*scaleY/180,  width*scaleX,height*scaleY); // Dibujar la imagen desde el centro
        ctx.restore();
    };
    img.src = src;
    ctx.restore();
}
