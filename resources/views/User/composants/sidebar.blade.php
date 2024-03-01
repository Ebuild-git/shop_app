<style>
    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 2;
        top: 0;
        left: 0;
        background-color: #ffffff;
        overflow-x: hidden;
        transition: 0.5s;
        -webkit-box-shadow: 6px 4px 56px -14px rgba(0, 0, 0, 0.9);
        -moz-box-shadow: 6px 4px 56px -14px rgba(0, 0, 0, 0.9);
        box-shadow: 6px 4px 56px -14px rgba(0, 0, 0, 0.9);

    }

    .sidenav a {
        text-decoration: none;
        color: #000000;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        color: #000000;
    }

    .sidenav .closebtn {
        text-align: right !important;
    }

    .side-link {
        color: #000000 !important;
    }

    .side-head {
        background-color: #e85d04;
        color: white !important;
        padding-top: 10px;
        padding-bottom: 10px
    }

    .arrow-icon-side {
        display: none;
        /* Cache la flèche par défaut */
    }

    .side-link:hover .arrow-icon-side {
        display: inline;
    }
    .side-link:hover {
        font-weight: bold;
    }

    @media screen and (max-height: 450px) {
        .sidenav {
            padding-top: 15px;
        }

        .sidenav a {
            font-size: 18px;
        }
    }
</style>
<div class="side-container" id="side-container">
    <div id="mySidenav" class="sidenav">
        <div class="side-head d-flex justify-content-between my-auto p-3">
            <div class="my-auto">
                <img src="/icons/icone-blanc.png" height="20px" alt="">
                @auth
                    Bonjour, {{ Auth::user()->name }}
                @else
                    Bonjour, Identifiez-vous
                @endauth
            </div>
            <div>
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            </div>
        </div>
        <br>
        <div class="p-3">
            <div class="h6 font-weight-bold">
                Choisir une catégorie
            </div>
            @php
                $categories = DB::table('categories')->select('titre', 'id')->get();
            @endphp
            @foreach ($categories as $item)
                @php
                    $sous_categories = DB::table('sous_categories')
                        ->where('id_categorie', $item->id)
                        ->select('titre', 'id')
                        ->get();
                @endphp
                <div class="d-flex justify-content-between">
                    <div id="cat-{{ $item->id}}">
                        <a href="#" class="side-link" onclick="toggleSubCategories({{ $item->id }})">
                            <i class="bi bi-arrow-right arrow-icon-side"></i>
                            {{ $item->titre }}
                        </a>
                    </div>
                    <div>
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>
                <div id="sub-cat-{{ $item->id }}" style="display: none;">
                    <ol class="ul-sous-cat">
                        @foreach ($sous_categories as $sous)
                            <li>
                                <a href="/shop?sous_categorie={{ $sous->id }}" class="side-link">
                                    {{ $sous->titre }}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endforeach
        </div>
        
        <script>
            function toggleSubCategories(categoryId) {
                var subCatDiv = document.getElementById("sub-cat-" + categoryId);
                if (subCatDiv.style.display === "none") {
                    subCatDiv.style.display = "block";
                } else {
                    subCatDiv.style.display = "none";
                }
            }
        </script>
        
    </div>
</div>
<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "350px";
        //get screen heigth
        var x = window.innerHeight;

        //egt screen width
        var y = window.innerWidth;
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
