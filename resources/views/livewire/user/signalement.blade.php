  <form wire:submit="signaler">
      @if (session()->has('error'))
          <div class="alert alert-danger small text-center">
              {{ session('error') }}
          </div>
          <br>
      @enderror
      @if (session()->has('info'))
          <div class="alert alert-info small text-center">
              {{ session('info') }}
          </div>
          <br>
      @enderror
      @if (session()->has('success'))
          <div class="alert alert-success small text-center">
              {{ session('success') }}
          </div>
          <br>
      @enderror

      <b>Motif</b>
      <select required wire:model="type" class="form-control shadow-none">
          <option value="">Choisir un motif</option>
          <option value="Fraude">Fraude</option>
          <option value="Spam">Spam</option>
      </select>
      @error('type')
          <small class="form-text text-danger">{{ $message }}</small>
      @enderror
      <b>Message</b>
      <textarea wire:model="message" class="form-control shadow-none" rows="6"></textarea>
      @error('message')
          <small class="form-text text-danger">{{ $message }}</small>
      @enderror
      <div class="modal-footer">
          <button type="submit" class="btn btn-sm btn-danger">
            <span wire:loading>
                <x-Loading></x-Loading>
            </span>
              Envoyer le signalement
              <i class="bi bi-arrow-right-circle-fill"></i>
          </button>
      </div>
</form>
