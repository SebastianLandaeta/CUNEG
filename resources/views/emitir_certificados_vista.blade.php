<x-layout>
    @section('title', 'Emitir Certificados')
    <h1 class="text-center">Emisión de certificado: "{{$curso->nombre}}"</h1>

    <div id="canvasContainer_{{$curso->id}}" class="container p-2" style="overflow-y: auto; max-height: 100vh;">
        <canvas id="previewCanvas_{{$curso->id}}" class="d-block mx-auto" width="800" height="600" style="border: 1px solid #ccc;"></canvas>
    </div>

    <h2 class="text-center p-2">Participantes: </h2>
    <ul>
        @forelse ($curso->participantes as $participante)
            <li><b>Nombre:</b> {{$participante->nombre}} - <b>Cédula</b>: {{$participante->cedula}} - {{$participante->email}}</li>
        @empty
            <li>PROBLEMAS AL ACCEDER A ESTA VISTA SIN PARTICIPANTES, GESTIONAR BACKEND...</li>
        @endforelse
    </ul>

    @if ($curso->certificado_cargado && $curso->lista_cargada)
        <div class="text-center p-2">
            <form action="{{ route('emitir_certificados.impresion', ['curso' => $curso]) }}" method="POST" target="_blank">
                @csrf
                <button type="submit" class="btn btn-primary mx-auto">
                    Emitir certificados
                </button>
            </form>
        </div>
    @endif
</x-layout>

<script src="{{ asset('js/construir_pizarra_ejemplo.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Event listener para los enlaces de visualización
        visualizarPizarra({!! $curso->id !!});
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
