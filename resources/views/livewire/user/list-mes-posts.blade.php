<div>
    <div>
        <div class="d-flex justify-content-between">
            <div>
                <h5>
                    Mes publicaions
                </h5>
            </div>
            <div>
                <form wire:submit="filtrer"> 
                    <div class="input-group mb-3">
                        <select class="form-control shadow-none" wire:model="etat">
                            <option value=""></option>
                            <option value="En modération">En modération</option>
                            <option value="Active">Active</option>
                        </select>
                        <input type="date" class="form-control shadow-none" wire:model="date">
                        <div class="input-group-append">
                            <button class="btn bg-red shadow-none" type="submit">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
                                <i class="bi bi-binoculars"></i> 
                                Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">titre</th>
                <th scope="col">Prix</th>
                <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $item)
            @php
                $photo = json_decode($item->photos, true);
            @endphp
            <tr>
                <th scope="row">
                    <img src="{{ Storage::url( $photo[0] ) }}" alt="" srcset="" style="height: 50px !important">
                </th>
                <td>{{ $item->titre }}</td>
                <td>{{ $item->prix }} Dt</td>
                <td>@mdo</td>
            </tr>
            @empty
            <tr>
                <th colspan="4">
                    <div class="alert alert-warning">
                        Aucun article trouvé pour ces critères de recherche.
                    </div>
                </th>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
