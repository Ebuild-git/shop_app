CountPanier();

function CountPanier() {
    $.get("/count_panier", function (response) {
        $(".CountPanier-value").text(response.count);
    });
}



function getColorName(colorCode) {
    $.ajax({
        url: "/color-name",
        type: "GET",
        data: { color: colorCode },
        success: function (response) {
            $("#colorName").text(`Color Name: ${response.name}`);
        },
        error: function (error) {
            console.error("Error:", error);
        },
    });
}
