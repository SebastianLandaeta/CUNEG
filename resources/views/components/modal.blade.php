<!-- Botón para abrir el formulario -->
<button type="button" class="{{ $ButtonType }}" data-toggle="modal" data-target="#{{ $ModalId }}">
    {{ $ButtonText }}
</button>

<!-- Modal con el formulario -->
<div class="modal fade" id="{{ $ModalId }}" role="dialog" aria-labelledby="{{ $ModalLabel }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $ModalLabel }}">{{ $ModalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
