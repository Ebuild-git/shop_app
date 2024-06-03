//initialisation
var check_luxury_only = "";
var key = $("#key").val();
var categorie = "";
var sous_categorie = "";
var region = "";
var ordre_prix = "";
var proprietes = "";


$(document).ready(function() {
    // Faire la requête initiale au chargement de la page
    fetchProducts();

    // Ajouter un écouteur d'événements pour la saisie dans le champ de recherche
    $('.key-input').on('input', function() {
        key = $('#key').val();
        fetchProducts();
    });
});

function ancre() {
    $('html,body').animate({
        scrollTop: $("#SugestionProprietes").offset().top
    }, 'slow');
}

function choix_ordre_prix(ordre) {
    ordre_prix = ordre;
    fetchProducts();
}

function check_luxury() {
    check_luxury_only = "true";
    fetchProducts();
}

function select_region(id) {
    region = id;
    fetchProducts();
}

function select_sous_categorie(id) {
    sous_categorie = id;
    fetchProducts();
    ancre();
}

function filtre_propriete(nom) {
    proprietes = nom;
    fetchProducts();
}

function select_categorie(id) {
    categorie = id;
    sous_categorie = "";
    fetchProducts();
}

function fetchProducts(page = 1) {
    $.post(
        "/recherche?page=" + page, {
            key: key,
            region: region,
            ordre_prix: ordre_prix,
            check_luxury: check_luxury_only,
            categorie: categorie,
            proprietes: proprietes,
            sous_categorie: sous_categorie,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, // Passer la valeur de la recherche comme paramètre
        function(data, status) {
            if (status === "success") {
                $(".rows-products").empty();
                $("#SugestionProprietes").empty();
                $(".rows-products").html(data.html);
                $("#total_post").text(data.total);
                renderPagination(data.data);
                $("#total_show_post").text(data.count_resultat);
                if (data.SugestionProprietes) {
                    $("#SugestionProprietes").html(data.SugestionProprietes);
                }
            }
        }
    );
}





function renderPagination(data) {
    const paginationControls = $('#pagination-controls');
    paginationControls.empty();
    
    //lancer la pagination uniquement si on a minimun un article
    if (data.data.length > 0) {
        if (data.current_page > 1) {
            paginationControls.append('<li data-page="' + (data.current_page - 1) + '">Précédent</li>');
        }

        for (let i = 1; i <= data.last_page; i++) {
            const activeClass = data.current_page === i ? 'active' : '';
            paginationControls.append('<li data-page="' + i + '" class="' + activeClass + '">' + i + '</li>');
        }

        if (data.current_page < data.last_page) {
            paginationControls.append('<li data-page="' + (data.current_page + 1) + '">Suivant</li>');
        }
    }

}



$(document).on('click', '.pagination li', function() {
    const page = $(this).data('page');
    fetchProducts(page);
});