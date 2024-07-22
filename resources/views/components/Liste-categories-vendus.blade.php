<div>
    @forelse ($categories as $categorie)
    <div class="card mb-1">
        {{ $categorie['nom']}}
    </div>
    @empty
    <div class="p-3 text-center">
        <img width="80" height="80" src="https://img.icons8.com/dotty/80/008080/categorize.png" alt="categorize" /> <br>
        Aucune catégorie trouvée!
    </div>
    @endforelse
</div>