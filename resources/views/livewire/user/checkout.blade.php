<div>
    <div class="row">
        <div class="col-sm-4">
            <div>
                <div class="d-flex justify-content-between">
                    <h4>Adresse de livraison</h4>
                    <a href="{{ route('mes_informations') }}" class="color">
                        <i class="bi bi-pencil-square"></i>
                        Modifié mes informations
                    </a>
                </div>

                <div class="d-flex justify-content-between">
                    <div>
                        <b>Adresse : </b> {{ Auth::user()->adress ?? 'N/A' }} <br>
                        <b>Numéro de téléphone :</b> {{ Auth::user()->phone_number ?? 'N/A' }} <br>
                    </div>
                </div>
            </div>
            <br>
            <b>Nombre d'article :</b> {{ $nbre_article }} <br><br>

        </div>
        <div class="col-sm-8">
            <div class="d-flex justify-content-end">
                <div>
                    <h3 class="color">
                        <b>{{ $total }} DH</b>
                        <span wire:loading>
                            <x-Loading></x-Loading>
                        </span>
                    </h3>
                </div>
            </div>


            <table class="table text-capitalize">
                @forelse ($articles_panier as $item)
                    <tr>
                        <td style="width: 50px">
                            <div class="avatar avatar-lg">
                                <img src="{{ Storage::url($item['photo']) }}" alt="..." class="img-fluid circle">
                            </div>
                        </td>
                        <td>
                                <a href="/post/{{ $item['id'] }}/{{ $item['titre'] }}" class="color">
                                    {{ $item['titre'] }}
                                </a>
                        </td>
                        <td>
                            <i> {{ $item['prix'] }} DH</i>
                        </td>
                        <td style="text-align: right">
                            <button class="btn btn-sm btn-danger p-1" wire:click="delete( {{ $item['id'] }} )">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-2">
                            <div class="alert alert-warning text-center">
                                Votre panier est vide !
                            </div>
                        </td>
                    </tr>
                @endforelse
            </table>
            @include('components.alert-livewire')
            <br>
            <div class="d-flex justify-content-end">

                <button class="btn btn-danger btn-sm stretched-link" @disabled($nbre_article <= 0) wire:click="vider()">
                    <span wire:loading.remove>
                        Vider le panier
                    </span>
                    
                </button>
                    <button class="btn btn-white stretched-link hover-black" @disabled($nbre_article <= 0) wire:click="valider()">
                        <span wire:loading>
                            Soumission des commandes....
                        </span>
                        
                        <span wire:loading.remove>
                            Valider mes commandes !
                        </span>
                        
                    </button>

            </div>
        </div>
    </div>
</div>