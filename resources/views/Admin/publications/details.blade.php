@extends('Admin.fixe')
@section('title', 'Publication |' . $post->titre)

@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Publication /</span> {{ $post->titre }}</h4>
        <!-- Bootstrap Table with Header - Dark -->
        <div class=" container card">
            <h5 class="card-header">
                {{ $post->titre }}
            </h5>
            <hr>
            <div class="row">
                <div class="col-sm-8">
                    <div>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"
                            style="background-color: #25293c;">
                            <div class="carousel-inner">

                                @foreach ($post->photos ?? [] as $key => $image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}"
                                        style="text-align: center !important">
                                        <img class="" src="{{ Storage::url($image) }}" style="max-height: 400px;">
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-row bd-highlight ">
                        @foreach ($post->photos ?? [] as $key => $image)
                                <div class="card-image-details-post">
                                    <img src="{{ Storage::url($image) }}" alt="" srcset="">
                                </div>
                        @endforeach
                    </div>
                    <br>
                    <p>
                        {!! $post->description !!}
                    </p>
                </div>
                <div class="col-sm-4 p-3 text-capitalize">
                    <div class="alert alert-dark" role="alert">
                        <div class="row">
                            <div class="col">
                                <i class="bi bi-telephone"></i> Contact<br> {{ $post->user_info->phone_number }}
                            </div>
                            <div class="col">
                                <i class="bi bi-envelope"></i> Email<br> {{ $post->user_info->email }}
                            </div>
                        </div>
                    </div>
                    <b>Titre : </b> {{ $post->titre }} <br>
                    <b>Prix : </b> {{ $post->prix }} DT <br>
                    <b>Sous-Catégorie :</b> {{ $post->sous_categorie_info->titre ?? "N/A"}} <br>
                    <b>Catégorie :</b> {{ $post->sous_categorie_info->categorie->titre ?? "N/A"}} <br>
                    <b>Région : </b> {{ $post->region->nom ?? "N/A" }} <br>
                    <b>Etat :</b> {{ $post->etat }}
                    <div class="p-2">
                        <hr>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="d-flex align-items-start">
                                        <div class="avatar me-2">
                                            <img src="{{ Storage::url($post->user_info->avatar) }}" alt="Avatar"
                                                class="rounded-circle">
                                        </div>
                                        <div class="me-2 ms-1">
                                            <h6 class="mb-0">
                                                {{ $post->user_info->name }}
                                                @if ($post->user_info->certifier == 'oui')
                                                    <img width="14" height="14"
                                                        src="https://img.icons8.com/sf-regular-filled/48/40C057/approval.png"
                                                        alt="approval" title="Certifié" />
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                {{ $post->user_info->GetPosts->count() }} publications.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <button class="btn btn-label-primary btn-icon btn-sm waves-effect"
                                            onclick="document.location.href='/admin/client/{{ $post->user_info->id }}/view'">
                                            <i class="ti ti-eye ti-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @livewire('DetailsPublicationAction', ['id' => $post->id])
                </div>
            </div>
            <br><br>
        </div>
        <!--/ Bootstrap Table with Header Dark -->

        <hr class="my-5" />


    </div>
    <!--/ Content -->


@endsection
@section('script')
    <script src="/assets-admin/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets-admin/vendor/libs/popper/popper.js"></script>
    <script src="/assets-admin/vendor/js/bootstrap.js"></script>
    <script src="/assets-admin/vendor/libs/node-waves/node-waves.js"></script>
    <script src="/assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/assets-admin/vendor/libs/hammer/hammer.js"></script>
    <script src="/assets-admin/vendor/libs/i18n/i18n.js"></script>
    <script src="/assets-admin/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets-admin/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/assets-admin/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="/assets-admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="/assets-admin/js/main.js"></script>

    <!-- Page JS -->
    <script src="/assets-admin/js/app-logistics-dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card-image-details-post{
            width: 50px;
            height: 50px;
            overflow: hidden;
            border-radius: 10px;
            margin: 5px;
        }
        .card-image-details-post img{
            width: 100%;
            border-radius: 10px;
        }
        .card-image-details-post img:hover{
            scale: 1.5;
        }
    </style>
@endsection
