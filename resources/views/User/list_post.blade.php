@extends('User.fixe')
@section('titre', 'Mes annonces')
@section('body')

    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Mes annonces
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="p-3">
            <div>
                <form method="POST" action="{{ route('post.mes-post') }}">
                    <div class="input-group mb-3">
                        @csrf
                        <select class="form-control sm  cusor" name="statut">
                            <option value=""></option>
                            <option value="validation">En validation</option>
                            <option value="vente">En cour de vente</option>
                            <option value="vendu">Vendu</option>
                            <option value="livraison">En cour de Livraison</option>
                            <option value="livré">Déja livré</option>
                        </select>
                        <input type="text" class="form-control sm cusor" placeholder="Année / Mois"  onfocus="(this.type='month')"
                        onblur="(this.type='text')" id="monthInput" 
                            value="{{ $date ? $date : null }}" name="date">
                        <div class="input-group-append">
                            <button class="btn bg-red p-2" type="submit">
                                <i class="bi bi-binoculars"></i>
                                Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <table class="table">
                <thead style="background-color: #008080;color: white !important;">
                    <tr>
                        <th scope="col" style="width: 51px;"></th>
                        <th scope="col">Article</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Décision</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $item)
                        <tr>
                            <th scope="row">
                                <div class="avatar-small-product">
                                    <img src="{{ Storage::url($item->photos[0] ?? '') }}" alt="avtar">
                                </div>
                            </th>
                            <td>
                                <b>
                                    <a href="/post/{{ $item->id }}" class="link">{{ $item->titre }}</a>
                                </b> <br>
                                <span class="small">
                                    <i>Publié le {{ $item->created_at }}</i>
                                </span>
                            </td>
                            <td class="text-capitalize">
                                <x-AnnonceStatut :statut="$item->statut"></x-AnnonceStatut>
                            </td>
                            <td>
                                @if ($item->old_prix)
                                    <b class="text-danger">
                                        {{ $item->prix }} DH
                                    </b>
                                    <br>
                                    <strike class="color strong">
                                        {{ $item->old_prix }} DH
                                    </strike>
                                @else
                                    {{ $item->prix }} DH
                                @endif
                            </td>
                            <td>
                                @if ($item->id_motif != null)
                                    <span class="text-secondary cusor" onclick="get_posts_motifs({{ $item->id }})">
                                        <b>
                                            <i class="bi bi-eye"></i>
                                            Voir les motifs
                                        </b>
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if ($item->propositions->count() > 0)
                                    <a href="/publication/{{ $item->id }}/propositions">
                                        <button class="btn btn-sm btn-dark">
                                            <i class="bi bi-plug-fill"></i>
                                            Propositions ( {{ $item->propositions->count() }} )
                                        </button>
                                    </a>
                                @endif
                                @if ($item->sell_at == null)
                                    <button class="btn btn-sm btn-info" onclick="Update_post_price({{ $item->id }})">
                                        <i class="bi bi-pencil-square"></i>
                                        Réduire le prix
                                    </button>
                                @endif
                                @if ($item->statut == 'validation' || $item->statut == 'vente')
                                    <button class="btn btn-sm bg-red" wire:click="delete({{ $item->id }})"
                                        wire:confirm="Voulez-vous supprimer cette publication ?">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="6">
                                <div class="alert alert-info text-center">
                                    <img width="100" height="100"
                                        src="https://img.icons8.com/ios/100/008080/empty-box.png" alt="empty-box" />
                                    <br>
                                    Aucun article trouvé pour ces critères de recherche.
                                </div>
                            </th>
                        </tr>
                    @endforelse
                </tbody>
            </table>



            <script>
                $(document).ready(function() {
                    $(".month-input").on('click', function() {
                        //make click on month-btn
                        $('#month-btn').trigger("click");
                        alert('dd');
                    });
                });
            </script>
           

        </div>

    </div>
@endsection
