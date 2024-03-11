<div class="row">
    @include('components.alert-livewire')
    <div class="col-sm-8">
        <table class="table">
            <thead class="table-dark">
                <th>#</th>
                <th>Nom</th>
                <th>Type</th>
                <td>Actions</td>
            </thead>
            <tbody>
                @forelse ($prorietes as $proriete)
                    <tr>
                        <td></td>
                        <td> {{ $proriete->nom }} </td>
                        <td>
                            @if ($proriete->type == "number")
                                Nombre
                            @elseif( $proriete->type == "color")
                                Couleur
                            @else
                                Texte
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger" wire:click="delete( {{$proriete->id}} )">
                                x
                            </button>
                        </td>
                    </tr>
                @empty
                    
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="col-sm-4">
        <h5>
            Nouuvelle propriété
        </h5>
        <form wire:submit="create">
            <div>
                <label class="form-label">Titre</label>
                <input type="text" wire:model="nom" class="form-control @error('nom') is-invalid @enderror"
                    required />
                @error('nom')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div>
                <label class="form-label">Type de la propriété</label>
                <select wire:model="type" class="form-control @error('type') is-invalid @enderror" required>
                    <option value=""></option>
                    <option value="text">Texte</option>
                    <option value="number">nombre</option>
                    <option value="color">Couleur</option>
                </select>
                @error('type')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <br>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <x-loading></x-loading>
                    Enregistrer
                </button>
              </div>
        </form>
    </div>
</div>
