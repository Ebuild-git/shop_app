
    <!-- Modal 1-->
    <div
      class="modal fade"
      id="modalToggle-{{ $item->id }}"
      aria-labelledby="modalToggleLabel"
      tabindex="-1"
      style="display: none"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalToggleLabel">
                {{ $item->titre}}
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @livewire('FormUpdateCategorie', ['id' => $item->id])
          </div>
        </div>
      </div>
    </div>

