<!-- resources/views/participantes/create.blade.php -->
<x-layout>
    @section('title', 'Generar QR de consulta')
    <div class="container">
        <h2>Agregar Participante</h2>
        <form action="{{ route('participantes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tipo_documento">Tipo de Documento</label>
                <input type="text" name="tipo_documento" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="numero_documento">Número de Documento</label>
                <input type="text" name="numero_documento" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</x-layout>

