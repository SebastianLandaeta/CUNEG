<x-layout>
    @section('title', 'Generar QR de consulta')
    <h1 class="text-center">Pagina de prueba Generación QR</h1>

    <div class="container">
    <h2 class="text-center mt-4">Generación QR Específica</h2>

    <form id="qrForm">
        @csrf
        <div class="d-flex flex-column align-items-center">
            <div class="m-3 text-center">
                <label for="idCurso" class="form-label">ID del curso</label>
                <input type="number" class="form-control" name="idCurso" style="width: 200px;" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>

            <div class="m-3 text-center">
                <label for="cedulaEstudiante" class="form-label">Cedula del estudiante</label>
                <input type="number" class="form-control" name="cedulaEstudiante" style="width: 200px;" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <button type="button" onclick="generateQRCode()" class="btn btn-primary mt-3 text-center" id="generateButton">Generar QR de consulta</button>
        </div>
    </form>

    <div id="qrContainer" class="text-center mt-4">
        <!-- Código QR generado -->
    </div>
</div>


    <script>
        function generateQRCode() {
            // Obtener los valores de los campos del formulario
            var idCurso = document.getElementsByName('idCurso')[0].value.trim();
            var cedulaEstudiante = document.getElementsByName('cedulaEstudiante')[0].value.trim();

            // Verificar si ambos campos tienen datos
            if (idCurso !== '' && cedulaEstudiante !== '') {
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
                    .then(function (response) {
                        return response.blob();
                    })
                    .then(function (svgBlob) {
                        var svgURL = URL.createObjectURL(svgBlob);
                        var objectElement = document.createElement('object');
                        objectElement.data = svgURL;
                        objectElement.type = 'image/svg+xml';
                        qrContainer.appendChild(objectElement); // Agregar el nuevo código QR al contenedor

                        // Habilitar el botón nuevamente después de que se haya generado el código QR
                        generateButton.disabled = false;
                    })
                    .catch(function (error) {
                        console.error('Error al realizar la solicitud:', error);
                        // En caso de error, habilitar el botón nuevamente para permitir más intentos
                        generateButton.disabled = false;
                    });
            } else {
                // Mostrar un mensaje de alerta o realizar alguna acción si ambos campos no están llenos
                alert('Por favor, completa ambos campos antes de generar el código QR.');
            }
        }
    </script>
</x-layout>
