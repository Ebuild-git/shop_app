@extends('User.fixe')
@section('titre', 'Contact')
@section('body')

    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('contact')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->

    <!-- ======================= Contact Page Detail ======================== -->
    <section class="middle">
        <div class="container">


            <div class="row align-items-start justify-content-between" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">

                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="card-wrap-body mb-4">
                        <p>{{ $configuration->adresse ?? '' }}</p>

                    </div>

                    <div class="card-wrap-body mb-3">
                        <table>
                            <tr >
                                <td class="pr-2">
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios-filled/50/008080/chat.png" alt="chat" />
                                </td>
                                <td>
                                    <span class="h6">
                                        <b>{{ __('chat') }}</b><br>
                                    </span>
                                    <b>{{ $configuration->phone_number ?? 'xxxxxxx' }}</b>
                                </td>
                            </tr>
                        </table> <br>
                        <table>
                            <tr>
                                <td>
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios-filled/50/008080/circled-envelope.png"
                                        alt="circled-envelope" />
                                </td>
                                <td>
                                    <span class="h6">
                                        <b>{{ __('email1') }}</b><br>
                                    </span>
                                    <b>{{ $configuration->email ?? 'xxxxx@gmail.com' }}</b>
                                </td>
                            </tr>
                        </table> <br>
                        <table>
                            <tr>
                                <td>
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/glyph-neue/50/008080/ringer-volume.png"
                                        alt="ringer-volume" />
                                </td>
                                <td>
                                    <span class="h6">
                                        <b>{{ __('call1') }}</b><br>
                                    </span>
                                    <b>{{ $configuration->phone_number ?? 'xxxxxxx' }}</b>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="card-wrap-body mb-3">

                        <h4 class="ft-medium mb-3 ">
                            {!! __('find_shopiner_on') !!}
                        </h4>
                        <div class="text-center">
                            @if ($configuration->facebook)
                                <a href="{{ $configuration->facebook }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/facebook--v1.png"
                                        alt="facebook--v1" />
                                </a>
                            @endif
                            @if ($configuration->linkedin)
                                <a href="{{ $configuration->linkedin }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/linkedin.png" alt="linkedin" />
                                </a>
                            @endif
                            @if ($configuration->tiktok)
                                <a href="{{ $configuration->tiktok }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/tiktok--v1.png" alt="tiktok--v1" />
                                </a>
                            @endif
                            @if ($configuration->instagram)
                                <a href="{{ $configuration->instagram }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/instagram-new--v1.png"
                                        alt="instagram-new--v1" />
                                </a>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12">
                    <div class="sec_title position-relative ">
                        <h2 class="ft-bold pt-3">{{ __('leave_us_message') }}</h2>
                    </div>

                    <form class="row">

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('your_name') }}</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('your_email_address') }}</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('subject') }}</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('message') }}</label>
                                <textarea class="form-control ht-80"></textarea>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <button type="button" class="btn bg-red">
                                    {{ __('send_message') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </section>
    <!-- ======================= Contact Page End ======================== -->

@endsection
