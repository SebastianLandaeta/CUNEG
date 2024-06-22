
@if(isset($ButtonType) && $ButtonType)
    <!-- Button trigger modal -->
    <button type="button" class="{{ $ButtonType }}" data-bs-toggle="modal" data-bs-target="#{{ $ModalId }}">
        {{ $ButtonText }}
    </button>
@endif

@if(isset($LinkType) && $LinkType)
    <!-- Anchor link to trigger modal -->
    <a href="#" class="{{ $LinkType }}" data-bs-toggle="modal" data-bs-target="#{{ $ModalId }}">
        {{ $LinkText }}
    </a>
@endif

<!-- Modal -->
<div class="modal fade" id="{{ $ModalId }}" tabindex="-1" aria-labelledby="{{ $ModalLabel }}" aria-hidden="true">
    <div class="{{ $ModalSize }}" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="{{ $ModalLabel }}">{{ $ModalTitle }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
    </div>
  </div>
</div>
