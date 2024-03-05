<nav class="row p-3">
    <div class="col-sm-8 mx-auto">
        <div class="div-big-recherche p-2">
            <form id="recherche-form">
                <table class="w-100">
                    <tr>
                        <td>
                            <input type="text" wire:model="key" class=" perso-s-input w-100 shadow-none" id="searchInput"
                                placeholder="Rechercher d'un article">

                        </td>
                    </tr>
                    <tr>
                        <td colspan="">
                            <div class="small text-muted div-avec-troncature">
                                @forelse ($topSubcategories as $item)
                                    <i class="bi bi-search"></i>
                                    <span class="color-orange cursor-pointer"
                                        onclick="set_value('{{ $item->titre }}')">
                                        {{ $item->titre }} ,
                                    </span>
                                @empty
                                @endforelse
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</nav>

<style>
    .div-big-recherche {
        border-radius: 10px;
        border: solid 1px #e85b0463;
        padding-left: 20px;
        -webkit-box-shadow: inset 0px 0px 7px 1px rgba(0, 0, 0, 0.41);
        -moz-box-shadow: inset 0px 0px 7px 1px rgba(0, 0, 0, 0.41);
        box-shadow: inset 0px 0px 7px 1px rgba(0, 0, 0, 0.41);
    }

    .perso-s-input {
        background-color: unset;
        border: none;
        padding: ;
        outline-offset: unset;
        outline: unset;
        outline-color: unset;
        outline-style: none;
        font-weight: 500;
    }

    .btn-recherche-shopp {
        border: none;
        border-radius: 9px;
        border: solid 1px #e85b0463;
        outline: unset;
        outline-color: unset;
        outline-style: none;
    }
</style>
<script>
    function redirectToSearch() {
        let input = document.getElementById("searchInput");
        if (input.value != "") {
            window.location.href = "/shop?key=" + encodeURIComponent(input.value);
        } else {
            //set boostrap invalid filed input
            $(input).addClass('is-invalid');
        }
    }

    function set_value(titre) {
        var x = document.getElementById("searchInput");
        x.value = titre;
    }

    // get SearchInput value when a tap enter in recherche-form
    $('#recherche-form').on('keyup', function(event) {
        event.preventDefault();
        if (event.keyCode == '13') {
            redirectToSearch();
        }
    });
</script>
