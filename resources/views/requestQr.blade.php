<x-layout>
    <h1 class="text-center">Pagina de prueba Generacion QR</h1>

    <div class="container">
        <form id="qrForm">
            @csrf
            <div class="mb-3">
                <label for="idCurso" class="form-label">ID del curso</label>
                <input type="text" class="form-control" name="idCurso" required>
            </div>

            <div class="mb-3">
                <label for="cedulaEstudiante" class="form-label">Cedula del estudiante</label>
                <input type="text" class="form-control" name="cedulaEstudiante" required>
            </div>
            
            <div class="clo-md-6 d-flex align-items-center justify-content-center">
            <button type="button" onclick="generateQRCode()" class="btn btn-primary mt-3 text-center" id="generateButton">Generar QR de consulta</button>
            </div>
        </form>

        <div id="qrContainer" class="text-center mt-4">
            <!-- Aquí se mostrará el código QR generado -->
        </div>
    </div>

    <script>
        function generateQRCode() 
        {
            // Obtener el botón y el contenedor donde se muestra el código QR
            var generateButton = document.getElementById('generateButton');
            var qrContainer = document.getElementById('qrContainer');

            // Deshabilitar el botón durante la solicitud
            generateButton.disabled = true;

            // Vaciar el contenido actual del contenedor qrContainer
            qrContainer.innerHTML = '';

            // Obtener el formulario y los datos del mismo
            var form = document.getElementById('qrForm');
            var formData = new FormData(form);

            // Realizar la solicitud para generar el código QR
            fetch('{{ route("qr.search.generate") }}', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.blob();
            })
            .then(function(svgBlob) {
                var svgURL = URL.createObjectURL(svgBlob);
                var objectElement = document.createElement('object');
                objectElement.data = svgURL;
                objectElement.type = 'image/svg+xml';
                qrContainer.appendChild(objectElement); // Agregar el nuevo código QR al contenedor

                // Habilitar el botón nuevamente después de que se haya generado el código QR
                generateButton.disabled = false;
            })
            .catch(function(error) {
                console.error('Error al realizar la solicitud:', error);
                // En caso de error, habilitar el botón nuevamente para permitir más intentos
                generateButton.disabled = false;
            });
        }
    </script>

</x-layout>
