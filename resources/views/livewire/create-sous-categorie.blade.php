<div class="row">
    <div class="col-sm-8">
        <div class="table-responsive text-nowrap ">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Titre</th>
                        <th>Articles</th>
                        <th>Description</th>
                        <th></th>
                    </tr>
                </thead>
                @forelse ($liste as $item)
                    <tr>
                        <td> {{ $item->titre }} </td>
                        <td> {{ $item->getPost->count() }} </td>
                        <td> {{ $item->description }} </td>
                        <td>
                            <button class="btn btn-danger"  wire:confirm="Voullez vous supprimer ?" wire:click="delete( {{ $item->id }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-info">
                                Aucun résultat !
                            </div>
                        </td>
                        </td>
                    </tr>
                @endforelse
            </table>
        </div>

        </div>
        <div class="col-sm-4">
            @include('components.alert-livewire')
            <form wire:submit = "save">
                <div>
                    <label class="form-label" for="modalAddCardName">Titre</label>
                    <input type="text" wire:model="titre" class="form-control" required />
                    @error('titre')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="modalAddCardName">Description</label>
                    <textarea class="form-control" wire:model="description" rows="5"></textarea>
                    @error('description')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
                        <span wire:loading>
                            <x-loading></x-loading>
                        </span>
                        Enregistrer
                    </button>
                </div>
            </form>

        </div>
    </div>
