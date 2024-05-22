//ouvrir le modal pour modifier le prix du post
function Update_post_price(id){
    //envoyer mon id au composant update price
    Livewire.dispatch('setPostId', {id: id});
    $("#Modal-Update-Post-Price").modal('toggle');
}


function add_cart(id) {
    $.get(
        "/add_panier",
        {
            id: id,
        },
        function (data, status) {
            if (status) {
                CountPanier();
                Swal.fire({
                    position: "center",
                    icon: false,
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: "swal-wide",
                });
                Livewire.dispatch("PostAdded");
            }
        }
    );
}


//retiitrer une publication liker de ma liste de like
function remove_liked(id) {
    $.get(
        "remove_liked",
        {
            id_like: id,
        },
        function (data, status) {
            if (status) {
                $("#tr-" + id).hide("slow");
                Swal.fire({
                    position: "center",
                    icon: false,
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: "swal-wide",
                });
            }
        }
    );
}

//retiitrer une publication de ma liste de favoris
function remove_favoris(id) {
    $.get(
        "/remove_favoris",
        {
            id_favoris: id,
        },
        function (data, status) {
            if (status) {
                $("#tr-" + id).hide("slow");
                Swal.fire({
                    position: "center",
                    icon: false,
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: "swal-wide",
                });
            }
        }
    );
}

$(document).ready(function () {
    // Ajouter un post à mes favoris
    $(".btn-add-favoris").on("click", function () {
        var button = $(this);
        var id_post = button.data("id");
        $.get(
            "/ajouter_favoris",
            {
                id_post: id_post,
            },
            function (data, status) {
                if (status === "success") {
                    if (data.action == "ajouté") {
                        button.addClass("btn-favoris-added");
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                            customClass: "swal-wide",
                        });
                    } else {
                        button.removeClass("btn-favoris-added");
                        Swal.fire({
                            position: "center",
                            icon: "warning",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                        });
                    }
                   
                }
            }
        );
    });


    //ajouter un like a un post
    $(".btn-like-post").on("click", function () {
        var button = $(this);
        var id_post = button.data("id");
        var span = button.find('span.count');
        $.get(
            "/like_post",
            {
                id_post: id_post,
            },
            function (data, status) {
                if (status === "success") {
                    span.text(data.count);
                    if (data.action == "ajouté") {
                        button.addClass("btn-favoris-added");
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                            customClass: "swal-wide",
                        });
                    } else {
                        button.removeClass("btn-favoris-added");
                        Swal.fire({
                            position: "center",
                            icon: "warning",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                        });
                    }
                   
                }
            }
        );
    });



    
});
