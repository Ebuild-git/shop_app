<form wire:submit="delete">
    <div class="alert alert-danger" >
        <i class="bi bi-trash3"></i>
        <span >
            {{ $titre }}
        </span>
    </div>
    <label for="">
        Selectionner le motif de suppression
    </label>
    <select wire:model="motif" class="form-control" required>
        <option value=""></option>
        <option>Contenu inapproprié</option>
        <option>Produit contrefait ou non authentique</option>
        <option>Description trompeuse de l'état de l'article</option>
        <option>Annonce multiple du même article</option>
        <option>Annonce de produits interdits ou illégaux</option>
        <option>Fraude ou activité suspecte</option>
        <option>Publicité non autorisée ou spam</option>
        <option>Information incorrecte sur la taille, la couleur, etc.</option>
        <option>Violation des droits d'auteur ou de la propriété intellectuelle</option>
        <option>Autres violations des politiques du site</option>
    </select>
    <br>
    <button type="submit" class="btn btn-danger">
        <span wire:loading>
            <x-loading></x-loading>
        </span>
        Effectuer la suppression
    </button>
</form>