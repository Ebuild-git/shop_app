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
            $("#colorName").text(`${response.name}`);
        },
        error: function (error) {
            console.error("Error:", error);
        },
    });
}


function getDirectColorName(colorCode){
    $.ajax({
        url: "/color-name",
        type: "GET",
        data: { color: colorCode },
        success: function (response) {
            return response.name;
        },
        error: function (error) {
            console.error("Error:", error);
        },
    });
}
