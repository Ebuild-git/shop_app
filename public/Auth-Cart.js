//ouvrir le modal pour modifier le prix du post
function Update_post_price(id){
    //envoyer mon id au composant update price
    Livewire.dispatch('setPostId', {id: id});
    $("#Modal-Update-Post-Price").modal('toggle');
}

CountPanier();
CountNotification();


// Ecoute des notificaions en live
Pusher.logToConsole = false;

var pusher = new Pusher('5b6f7ad6a8cf384098d9', {
    cluster: 'eu'
});

var channel = pusher.subscribe('my-channel-user-admin-{{ Auth::user()->id }}');
channel.bind('my-event', function(data) {
    CountNotification();
});




function CountPanier(){
    $.get(
        "/count_panier",
        function (data, status) {
            if (status === "success") { 
                $("#CountPanier-value").text(data.count);
                $("#Contenu-panier").html(data.html);
                $("#montant-panier").text(data.montant);
            }
        }
    );
}


function CountNotification(){
    $.get(
        "/count_notification",
        function (data, status) {
            if (status === "success") {
                $("#CountNotification-value").text(data.count);
            }
        }
    );
}



function remove_to_card(id){
    $.get(
        "/remove_to_card",
        {
            id: id,
        },
        function (data, status) {
            if (status) {
                CountPanier();
            }
        }
    );
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



function delete_notification(id){
    $.get(
        "/delete_notification",
        {
            id_notification: id,
        },
        function (data, status) {
            if (status) {
                $('#tr-'+id).hide("slow");
                CountNotification();
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


//recuperer la liste des motifs de refus d'un post
function get_posts_motifs(id) {
    $.get(
        "/list_motifs",
        {
            id_post: id,
        },
        function (response, status) {
            if (status === 'success' && response.statut) {
                console.log(response); // Affiche la réponse complète
                console.log(response.data); // Affiche la propriété 'data' de la réponse
                
                // Vérifiez si 'response.data' est défini et est un tableau
                if (Array.isArray(response.data)) {
                    // Ouvrir la modal Motifs-des-refus
                    $("#modal_motifs_des_refus").modal('toggle');
                    // Vider le contenu du tbody du tableau
                    $("#modal_motifs_des_refus-table tbody").html("");
                    
                    // Ajouter les motifs de refus au tbody du tableau
                    $.each(response.data, function (index, value) {
                        
                        $("#modal_motifs_des_refus-table tbody").append(
                            "<tr>" +
                            "<td>" + (value.motif || '') + "</td>" +
                            "<td>" + (value.created_at || '') + "</td>" +
                            "</tr>"
                        );
                    });
                } else {
                    console.error("Data is not an array:", response.data);
                }
            } else {
                console.error("Request failed with status or invalid 'statut':", status, response.statut);
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
