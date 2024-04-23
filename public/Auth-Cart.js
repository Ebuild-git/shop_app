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
                Livewire.dispatch('PostAdded');
            }
        }
    );
}


function add_like(id){
    $.get(
        "/like",
        {
            id_post: id,
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
            }
        }
    );
}