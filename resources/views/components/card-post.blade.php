@php
    $photo = json_decode($post->photos, true);
@endphp
<div class="{{ $class }} cursor-pointer" onclick="document.location.href='/post/{{ $post->id }}'">
    <div class=" card ">
        <div class="pb-1 p-1">
            <img src="{{ Storage::url($post->user_info->avatar)}}" style="height: 30px;width: 30px;border-radius: 100%" alt="">
            <span class="small">
                {{ $post->user_info->name }}
            </span>
        </div>
        <div class="home-post-cart">
            <img class="" alt="{{ $post->titre }}" src="{{ Storage::url($photo[0] ?? "") }}">
        </div>
        <div class="p-1">
            <div class="d-flex justify-content-between">
                <div>
                    <span class="text-red small">
                        <strong>{{ $post->prix }}</strong>  DH
                    </span>
                </div>
                <div>
                    <span class="text-muted small">
                        <i>{{ $post->etat }}</i>
                        <i class="bi bi-info-circle"></i>
                    </span>
                </div>
            </div>
            
            <h6 class="card-title text-capitalize" >
                {!! str_replace(' ', '&nbsp;', str_pad($post->titre, 50, ' ', STR_PAD_RIGHT)) !!}
            </h6>
            <p class="card-text small text-muted">
                <b>
                    <i class="bi bi-geo-alt"></i>
                </b> : {{ $post->ville }}<br>
                <b>
                    <i class="bi bi-grid-1x2"></i>
                </b> : {{ $post->sous_categorie_info->titre }} <br>
                <i class="bi bi-calendar3"></i> : {{ $post->created_at }}
                
            </p>

        </div>

    </div>
</div>
