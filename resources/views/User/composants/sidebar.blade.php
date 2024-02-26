<style>

    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #ffffff;
        overflow-x: hidden;
        transition: 0.5s;
        -webkit-box-shadow: 6px 4px 56px -14px rgba(0,0,0,0.9);
-moz-box-shadow: 6px 4px 56px -14px rgba(0,0,0,0.9);
box-shadow: 6px 4px 56px -14px rgba(0,0,0,0.9);

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

    @media screen and (max-height: 450px) {
        .sidenav {
            padding-top: 15px;
        }

        .sidenav a {
            font-size: 18px;
        }
    }
</style>
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
            Catégories
        </div>
        @php
            $categories = DB::table('categories')->select('titre', 'id')->get();
        @endphp
        @foreach ($categories as $item)
            <a href="#" class="side-link">{{ $item->titre }}</a>
        @endforeach
        <hr>
        <div class="h6 font-weight-bold">
            Sous-Catégories
        </div>
        @php
            $categories = DB::table('sous_categories')->select('titre', 'id')->get();
        @endphp
        @foreach ($categories as $item)
            <a href="#" class="side-link">{{ $item->titre }}</a>
        @endforeach

    </div>
</div>
<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "350px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
