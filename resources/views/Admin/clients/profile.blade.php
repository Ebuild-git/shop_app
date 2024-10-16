@extends('Admin.fixe')
@section('titre', 'Profil ' . $user->name)
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="py-3 mb-4">
            <span class="text-muted fw-light">Utilisateur /</span> {{ $user->username }}
        </h5>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        <img src="/assets-admin/img/pages/profile-banner.png" alt="Banner image" class="rounded-top" />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            <a href="#">
                                <img src="{{ $user->getAvatar() }}" alt="..."
                                    class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                            </a>
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>
                                        {{ $user->username }}
                                    </h4>
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item d-flex gap-1 cusor">
                                            <i class="ti ti-color-swatch"></i> {{ $user->email }}
                                        </li>
                                        <li class="list-inline-item d-flex gap-1"><i class="ti ti-map-pin"></i>
                                            {{ $user->ville ?? '/' }}</li>
                                            <li class="list-inline-item d-flex gap-1">
                                                <i class="ti ti-calendar"></i>
                                                {{ $user->gender == 'female' ? 'Inscrite' : 'Inscrit' }} le {{ \Carbon\Carbon::parse($user->created_at)->locale('fr')->translatedFormat('d F Y à H:i') }}
                                            </li>

                                    </ul>
                                </div>

                                {{-- <form action="{{ route('change_picture_statut') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                                    @if (is_null($user->photo_verified_at))
                                        <a href="javascript:void(0)">
                                            <button class="btn btn-success" type="submit">
                                                <i class="ti ti-camera me-1"></i> Accepter
                                            </button>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)">
                                            <button class="btn btn-danger" type="submit">
                                                <i class="ti ti-camera me-1"></i> Réfuser
                                            </button>
                                        </a>
                                    @endif
                                </form> --}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->

        <!-- Navbar pills -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);">
                            <i class="ti-xs ti ti-user-check me-1"></i>
                            Profil</a>
                    </li>
                </ul>
            </div>
        </div>
        <!--/ Navbar pills -->

        <!-- User Profile Content -->
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5">
                <!-- About User -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <small class="card-text text-uppercase">A propos</small>
                            <span>
                                <i class="bi bi-star-fill" style="color: #ffb74e;"></i>
                                {{ number_format($user->averageRating->average_rating ?? 0, 1) }}
                            </span>
                        </div>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-user text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Nom:</span>
                                <span>{{ ucfirst($user->firstname) }}</span>

                                &nbsp;
                                [ {{ $user->gender }} ]
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-user text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Prénom: </span>
                                <span>{{ ucfirst($user->lastname) }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-check text-heading"></i><span class="fw-medium mx-2 text-heading">Email
                                    vérifié:</span>
                                @if ($user->email_verified_at == null)
                                    <span class="badge bg-label-danger">
                                        Non
                                    </span>
                                @else
                                    <span class="badge bg-label-success">
                                        Oui
                                    </span>
                                @endif
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-flag text-heading"></i><span class="fw-medium mx-2 text-heading">
                                    Date de naissance:</span>
                                    <span class="ms-2">{{ \Carbon\Carbon::parse($user->birthdate)->locale('fr')->translatedFormat('d F Y') }}</span>
                            </li>
                            {{-- <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-file-description text-heading"></i><span
                                    class="fw-medium mx-2 text-heading">Adresse:</span>
                                <span>
                                    {!! $user->num_appartement ? 'App. ' . $user->num_appartement . ',' : '' !!}
                                    {!! $user->etage ? 'Étage ' . $user->etage . ',' : '' !!}
                                    {!! $user->nom_batiment ? $user->nom_batiment . ',' : '' !!}
                                    {!! $user->rue ? $user->rue . ',' : '' !!}
                                    {!! $user->address ?? '' !!}
                                </span>
                            </li> --}}
                            <li class="d-flex flex-column mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-file-description text-heading"></i>
                                    <span class="fw-medium mx-2 text-heading">Adresse:</span>
                                </div>
                                <span style="margin-left: 28px;">
                                    {!! $user->num_appartement ? 'App. ' . $user->num_appartement . ',' : '' !!}
                                    {!! $user->etage ? 'Étage ' . $user->etage . ',' : '' !!}
                                    {!! $user->nom_batiment ? $user->nom_batiment . ',' : '' !!}
                                    {!! $user->rue ? $user->rue . ',' : '' !!}
                                    {!! $user->address ?? '' !!}
                                </span>
                            </li>


                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-file-description text-heading"></i><span
                                    class="fw-medium mx-2 text-heading">Region:</span>
                                <span>{{ $user->region_info->nom ?? '/' }}</span>
                            </li>
                        </ul>
                        <small class="card-text text-uppercase">Contacts</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-phone-call"></i><span class="fw-medium mx-2 text-heading">Téléphone:</span>
                                <span>{{ $user->phone_number ?? '/' }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-mail"></i><span class="fw-medium mx-2 text-heading">Email:</span>
                                <span>{{ $user->email ?? '/' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ About User -->
                <!-- Profile Overview -->
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text text-uppercase">Aperçu</p>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-layout-grid"></i><span class="fw-medium mx-2">Publications :</span>
                                <span>{{ $user->GetPosts->count() }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Profile Overview -->
            </div>
            <div class="col-xl-8 col-lg-7 col-md-7">
                <!-- Projects table -->
                <div class="card mb-4">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-projects table border-top">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Photo</th>
                                    <th>Titre</th>
                                    <th>Date</th>
                                    <th>Prix</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>{{ 'P' . $post->id }}</td>
                                    <td>
                                        <div class="avatar me-2">
                                            <img src="{{ $post->FirstImage() }}" alt="{{ $post->titre }}" srcset=""
                                                class="rounded">
                                        </div>
                                    </td>

                                    <td> {{ $post->titre }} </td>
                                    <td>{{ $post->created_at }}</td>
                                    <td>
                                        @if ($post->old_prix)
                                            <span class="strong color strong">
                                                <strike>
                                                    {{ $post->getOldPrix() }}
                                                </strike> <sup>DH</sup>
                                            </span>
                                            <br>
                                            <span class="text-danger strong">
                                                {{ $post->getPrix() }} <sup>DH</sup>
                                            </span>
                                        @else
                                            <span class=" color strong ">
                                                {{ $post->getPrix() }} <sup>DH</sup>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                            onclick="document.location.href='/admin/publication/{{ $post->id }}/view'">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-3 text-center">
                                        Aucune publication pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                        <div class="p-3" {{ $posts->links('pagination::bootstrap-4') }} </div>
                    </div>
                </div>
                <!--/ Projects table -->
            </div>
        </div>
        <!--/ User Profile Content -->
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

    <!-- Main JS -->
    <script src="/assets-admin/js/main.js"></script>
@endsection
@section('css')

    <!-- Page CSS -->
    <link rel="stylesheet" href="/assets-admin/vendor/css/pages/page-profile.css" />

    <!-- Helpers -->
    <script src="/assets-admin/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/assets-admin/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/assets-admin/js/config.js"></script>
@endsection
