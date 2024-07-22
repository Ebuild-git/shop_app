<div>
    @forelse ($categories as $categorie)
    <div class="mb-3 p-2 my-auto">
        <h6>
            <i class="bi bi-chevron-double-right"></i>
            <b>
                {{ $categorie['nom']}}
            </b>
        </h6>
    </div>
    @empty
    <div class="p-3 text-center">
        <img width="80" height="80" src="https://img.icons8.com/dotty/80/008080/categorize.png" alt="categorize" /> <br>
        Aucune catégorie trouvée!
    </div>
    @endforelse
</div>