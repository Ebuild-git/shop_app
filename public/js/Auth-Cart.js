//ouvrir le modal pour modifier le prix du post
function Update_post_price(id) {
    //envoyer mon id au composant update price
    Livewire.dispatch("setPostId", { id: id });
    $("#Modal-Update-Post-Price").modal("toggle");
}

function sweet(message) {
    Swal.fire({
        position: "center",
        icon: false,
        text: message,
        showConfirmButton: false,
        timer: 2500,
        customClass: "swal-wide",
    });
}

CountPanier();
CountNotification();

// Ecoute des notificaions en live
Pusher.logToConsole = false;

var pusher = new Pusher("5b6f7ad6a8cf384098d9", {
    cluster: "eu",
});

var channel = pusher.subscribe("my-channel-user-admin-{{ Auth::user()->id }}");
channel.bind("my-event", function (data) {
    CountNotification();
});

//supprimer mon post
function delete_my_post(id) {
    Swal.fire({
        title: "Voulez-vous supprimer ?",
        text: "Vous ne pourrez pas revenir en arrière !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, supprimer",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(
                "/delete_my_post",
                {
                    id_post: id,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                function (data, status) {
                    if (data.success) {
                        $("#tr-post-" + id).hide("slow");
                        Swal.fire({
                            title: "Supprimé !",
                            text: "Votre annonce a été supprimé",
                            icon: "success",
                        });
                    } else {
                        Swal.fire({
                            title: "Erreur !",
                            text: "Une erreur est survenue",
                            icon: "error",
                        });
                    }
                }
            );
        }
    });
}

function CountPanier() {
    $.get("/count_panier", function (data, status) {
        if (status === "success") {
            $("#CountPanier-value").text(data.count);
            $("#Contenu-panier").html(data.html);
            $("#montant-panier").text(data.montant);

            if (data.count > 1) {
                $(".CountPanier-value").text(data.count + " articles");
            } else {
                $(".CountPanier-value").text(data.count + " article");
            }

            if (data.count > 0) {
                $("#empty-card-div").hide();
                $("#cart_select_items").show();
            } else {
                //si il ya aucun element dans le panier on cache les options
                $("#cart_select_items").hide();
                $("#empty-card-div").show();
            }
        }
    });
}

function CountNotification() {
    $.get("/count_notification", function (data, status) {
        if (status === "success") {
            $("#CountNotification-value").text(data.count);
        }
    });
}

function remove_to_card(id) {
    Swal.fire({
        title: "Es-tu sûr?",
        text: "Voulez vous réttiré ceci de votre panier ?",
        showCancelButton: true,
        confirmButtonColor: "#008080",
        cancelButtonColor: "#d33",
        confirmButtonText: "Valider ",
        cancelButtonText: "Annuler ",
    }).then((result) => {
        if (result.isConfirmed) {
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
    });
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
                });
                Livewire.dispatch("PostAdded");
                document.getElementById("Cart").style.display = "block";
                // affichier le message de success ajouter pour 10 secondes
                $("#div-success-add-card").show("slow");
                $("#div-success-add-card").html(data.message);
                if (data.exist) {
                    $("#add-cart-text-btn").text("Retirer du panier");
                    $("#btn-add-to-card").addClass("bg-dark");
                } else {
                    $("#add-cart-text-btn").text("Ajouter au panier");
                    $("#btn-add-to-card").removeClass("bg-dark");
                }
                setTimeout(function () {
                    $("#div-success-add-card").hide("slow");
                }, 7000);
            }
        }
    );
}

function delete_notification(id) {
    $.get(
        "/delete_notification",
        {
            id_notification: id,
        },
        function (data, status) {
            if (status) {
                $("#tr-" + id).hide("slow");
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
                if (data.count == 0) {
                    //reload page
                    location.reload();
                }
            }
        }
    );
}

//retitrer une publication de ma liste de favoris
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
                if (data.count == 0) {
                    //reload page
                    location.reload();
                }
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
            if (status === "success" && response.statut) {
                console.log(response); // Affiche la réponse complète
                console.log(response.data); // Affiche la propriété 'data' de la réponse

                // Vérifiez si 'response.data' est défini et est un tableau
                if (Array.isArray(response.data)) {
                    // Ouvrir la modal Motifs-des-refus
                    $("#modal_motifs_des_refus").modal("toggle");
                    // Vider le contenu du tbody du tableau
                    $("#modal_motifs_des_refus-table tbody").html("");

                    // Ajouter les motifs de refus au tbody du tableau
                    $.each(response.data, function (index, value) {
                        $("#modal_motifs_des_refus-table tbody").append(
                            "<tr>" +
                                "<td>" +
                                (value.motif || "") +
                                "</td>" +
                                "<td>" +
                                (value.created_at || "") +
                                "</td>" +
                                "</tr>"
                        );
                    });
                } else {
                    console.error("Data is not an array:", response.data);
                }
            } else {
                console.error(
                    "Request failed with status or invalid 'statut':",
                    status,
                    response.statut
                );
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
                        //change value of .text span in this bouton
                        button.find(".text").text("Retirer de mes favoris");
                    } else {
                        button.removeClass("btn-favoris-added");
                        Swal.fire({
                            position: "center",
                            icon: "warning",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        button.find(".text").text("Ajouter aux favoris");
                    }
                }
            }
        );
    });

    //ajouter un like a un post
    $(".btn-like-post").on("click", function () {
        var button = $(this);
        var id_post = button.data("id");
        var span = button.find("span.count");
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
function btn_like_post(id_post) {
    var button = $("#post-" + id_post);
    var span = button.find("span.count");

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
}

//supprimer toute les notification
function delete_all_notification() {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            title: "Es-tu sûr?",
            text: "Vous ne pourrez pas revenir en arrière !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, supprimer !",
            cancelButtonText: "Non",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                //go to url
                window.location.href = "/delete/all_notifications";
            }
            {
                result.dismiss === Swal.DismissReason.cancel;
            }
        });
}

/////////////////////////// localisation
var result_location = "";
function get_location() {
    if (navigator.geolocation) {
        $("#location-modal").modal("toggle");
        // Demander la localisation à l'utilisateur
        navigator.geolocation.getCurrentPosition(
            function (position) {
                // Récupérer les coordonnées de la position
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;

                // Initialiser la carte Leaflet
                let map = L.map("map-adresse").setView(
                    [latitude, longitude],
                    13
                );

                // Ajouter une couche de tuile (Mapbox Streets est gratuite)
                L.tileLayer(
                    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
                    {
                        maxZoom: 19,
                        attribution: "SHOPIN",
                    }
                ).addTo(map);

                // Ajouter un marqueur à la position
                L.marker([latitude, longitude])
                    .addTo(map)
                    .bindPopup("Votre position")
                    .openPopup();

                // Récupérer l'adresse textuelle à partir des coordonnées
                fetch(
                    `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`
                )
                    .then((response) => response.json())
                    .then((data) => {
                        result_location = data.display_name;
                        $("#val-adresse").text(result_location);
                    })
                    .catch((error) => {
                        console.error(
                            "Erreur lors de la récupération de l'adresse :",
                            error
                        );
                    });
            },
            function (error) {
                // En cas d'erreur
                $("#location-modal").modal("toggle");
                console.error(
                    "Erreur lors de la récupération de la localisation :",
                    error
                );
            }
        );
    } else {
        console.error(
            "La géolocalisation n'est pas prise en charge par ce navigateur."
        );
    }
}

function btn_accept_location() {
    Livewire.dispatch("UpdateUserAdresse", { adresse: result_location });
    sweet("Adresse accepté !");
    //close modal location-modal
    $("#location-modal").modal("toggle");
}
