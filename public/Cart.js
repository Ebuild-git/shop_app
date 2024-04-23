CountPanier();

function CountPanier() {
    $.get("/count_panier", function (response) {
        $(".CountPanier-value").text(response.count);
    });
}

