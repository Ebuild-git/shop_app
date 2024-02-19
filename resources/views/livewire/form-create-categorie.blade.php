<form wire:submit="creer">
    @csrf
    @if (session()->has('error'))
        <span class="text-danger small">
            {{ session('error') }}
        </span>
    @enderror
    @if (session()->has('success'))
        <span class="text-success small">
            {{ session('success') }}
        </span>
    @enderror
<br>
    <label for="">Titre de la catégorie</label>
    <input type="text" wire:model ="titre" class="form-control" required>
    @error('titre')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror

    <label for="">description </label>
    <textarea class="form-control" wire:model="description" rows="4" required></textarea>
    @error('description')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror

    <label for="">Image d'illustration </label>
    <input type="file" wire:model="photo" wire:target="photo" class="form-control" required>
    @error('photo')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror
    <br>
    <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
        <x-loading></x-loading>
        Enregistrer
    </button>
</form>
