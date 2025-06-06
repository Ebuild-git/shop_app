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
        title: deletePostTranslation.title,
        text: deletePostTranslation.text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: deletePostTranslation.confirm,
        cancelButtonText: deletePostTranslation.cancel,
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
                            title: deletePostTranslation.success_title,
                            text: deletePostTranslation.success_text,
                            icon: "success",
                        });
                    } else {
                        Swal.fire({
                            title: deletePostTranslation.error_title,
                            text: deletePostTranslation.error_text,
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
            $("#CountPanier-value-mobile").text(data.count);

            $("#Contenu-panier").html(data.html);
            $("#montant-panier").text(data.montant);

            if (data.count > 1) {
                $(".CountPanier-value").text("x" + data.count );
            } else {
                $(".CountPanier-value").text("x" + data.count);
            }

            if (data.count > 0) {
                $("#empty-card-div").hide();
                $("#cart_select_items").show();
            } else {
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
            $("#CountNotification-value-mobile").text(data.count);

        }
    });
}
function resetNotificationCount() {
    $("#CountNotification-value").text('0');
    $("#CountNotification-value-mobile").text('0');
}

function remove_to_card(id) {
    Swal.fire({
        // title: "Es-tu sûr?",
        title: `<small>${cartTranslations.title}</small>`,
        showCancelButton: true,
        confirmButtonColor: "#008080",
        cancelButtonColor: "#d33",
        confirmButtonText: cartTranslations.confirm,
        cancelButtonText: cartTranslations.cancel,
        iconHtml: '<i class="bi bi-info-circle-fill custom-info-icon"></i>',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get(
                "/remove_to_card",
                { id: id },
                function (data, status) {
                    if (status === "success") {
                        CountPanier();

                        if (data.status && !data.exist) {
                            $("#add-cart-text-btn").text(cartTranslations.success_add);
                            $("#btn-add-to-card").removeClass("bg-dark");

                            Swal.fire({
                                position: "center",
                                icon: "success",
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000,
                            });
                        }
                    }
                }
            );
        }
    });
}


function add_cart(id) {
    $.get(
        "/add_panier",
        { id: id },
        function (data, status) {
            if (status === "success") {
                CountPanier(); // Make sure this reflects the updated count

                Swal.fire({
                    position: "center",
                    icon: false,
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2500,
                });

                Livewire.dispatch("PostAdded");

                document.getElementById("Cart").style.display = "block";

                $("#div-success-add-card").show("slow").html(data.message);

                updateButtonState(data.exist);

                setTimeout(function () {
                    $("#div-success-add-card").hide("slow");
                }, 7000);
            }
        }
    );
}

function updateButtonState(isInCart) {
    var textRetire = document.getElementById('btn-add-to-card').getAttribute('data-retire');
    var textAjouter = document.getElementById('btn-add-to-card').getAttribute('data-ajouter');
    if (isInCart) {
        $("#add-cart-text-btn").text(textRetire);
        $("#btn-add-to-card").addClass("bg-dark");
    } else {
        $("#add-cart-text-btn").text(textAjouter);
        $("#btn-add-to-card").removeClass("bg-dark");
    }
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
                console.log(response);
                console.log(response.data);

                if (Array.isArray(response.data)) {
                    $("#modal_motifs_des_refus").modal("toggle");
                    $("#modal_motifs_des_refus-table tbody").html("");

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
    $(".btn-add-favoris").on("click", function () {
        var button = $(this);
        var id_post = button.data("id");
        const addText = button.data("add-text");
        const removeText = button.data("remove-text");
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
                        button.find(".text").text(removeText);
                    } else {
                        button.removeClass("btn-favoris-added");
                        Swal.fire({
                            position: "center",
                            icon: "warning",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        button.find(".text").text(addText);
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
            title: translations.delete_confirm_title,
            text: translations.delete_post_text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: translations.delete_post_confirm,
            cancelButtonText: translations.delete_post_cancel,
            reverseButtons: true,
            customClass: {
                confirmButton: 'custom-confirm-btn',
                cancelButton: 'custom-cancel-btn'
            },

        })
        .then((result) => {
            if (result.isConfirmed) {
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

        navigator.geolocation.getCurrentPosition(
            function (position) {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;

                let map = L.map("map-adresse").setView([latitude, longitude], 13);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution: "SHOPIN",
                }).addTo(map);

                L.marker([latitude, longitude])
                    .addTo(map)
                    .bindPopup("Votre position")
                    .openPopup();

                // Fetch the address from the coordinates using OpenStreetMap's Nominatim API
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`)
                    .then((response) => response.json())
                    .then((data) => {
                        result_location = data.display_name;
                        console.log("Location Result:", result_location);  // Log the full location result
                    })
                    .catch((error) => {
                        console.error("Error retrieving address:", error);
                    });
            },
            function (error) {
                $("#location-modal").modal("toggle");  // Hide the modal on error
                console.error("Error retrieving location:", error);
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
}

function btn_accept_location() {
    Livewire.dispatch("storeLocation", { city: result_location });
    sweet("Adresse acceptée!");
    $("#location-modal").modal("toggle");

}

function ShowPostsCatgorie(id) {
    //open CatégoriesPost modal
    $.ajax({
        url: "/category/post_user",
        data: {
            id_user: id,
        },
        type: "GET",
        success: function (response) {
            $("#content-liste").empty();
            $("#content-liste").html(response.html);
            $("#username_user_modal_categorie").html(response.username);
            $("#CategoryPostsModal").modal("toggle");
        },
        error: function () {
            console.log("Error while loading posts of this category !");
        },
    });
}

function toggleFavorite(postId) {
    $.ajax({
        url: '/ajouter_favoris',
        type: 'GET',
        data: {
            id_post: postId
        },
        success: function(response) {
            if (response.status) {
                // Update the UI
                const button = $(`#post-${postId}`);
                const countElement = button.find('.count');
                const currentCount = parseInt(countElement.text());

                // Toggle visual state and update count
                if (response.action === "ajouté") {
                    button.addClass('active');
                    countElement.text(currentCount + 1);
                } else {
                    button.removeClass('active');
                    countElement.text(currentCount - 1);
                }

                // Show centered alert with default styling
                Swal.fire({
                    text: response.message,
                    position: 'center',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    text: response.message,
                    position: 'center',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                text: 'An error occurred. Please try again.',
                position: 'center',
                showConfirmButton: false,
                timer: 2000
            });
        }
    });
}
