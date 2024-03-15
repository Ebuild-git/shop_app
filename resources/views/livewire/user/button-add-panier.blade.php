<button type="submit" class="btn btn-block custom-height bg-dark mb-2 " wire:click="add()">
    <i class="lni lni-shopping-basket mr-2"></i>
    <span wire:loading>
        Ajout...
    </span>
    
    <!-- Affiche le contenu normal lorsque isLoading est faux -->
    <span wire:loading.remove>
        Ajouter au panier
    </span>
</button>