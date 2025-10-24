@extends('layout.layout')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <a href="{{ url('links/' . $telegramId) }}">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="bi bi-telegram"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-2 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Telegram Links</p>
                                            <h4 class="card-title">
                                                <small>Active:</small>
                                                <span class="fs-6">{{ $activeTelegramLinks }}</span>
                                            </h4>
                                            <h4 class="card-title">
                                                <small>Blocked:</small>
                                                <span class="fs-6">{{ $blockedTelegramLinks }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ url('links/' . $facebookId) }}">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-info bubble-shadow-small">
                                            <i class="bi bi-facebook"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-2 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Facebook Links</p>
                                            <h6 class="card-title"><small>Active:</small> <span
                                                    class="fs-6">{{ $activeFacebookLinks }}</span></h6>
                                            <h6 class="card-title"><small>Blocked:</small> <span
                                                    class="fs-6">{{ $blockedFacebookLinks }}</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ url('links/' . $whatsappId) }}">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="bi bi-whatsapp"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-2 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Whatsapp Links</p>
                                            <h6 class="card-title"><small>Active:</small> <span
                                                    class="fs-6">{{ $activeWhatsappLinks }}</span></h6>
                                            <h6 class="card-title"><small>Blocked:</small> <span
                                                    class="fs-6">{{ $blockedWhatsappLinks }}</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ url('links/' . $instagramId) }}">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="bi bi-instagram"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-2 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Instagram Links</p>
                                            <h6 class="card-title"><small>Active:</small> <span
                                                    class="fs-6">{{ $activeInstagramLinks }}</span></h6>
                                            <h6 class="card-title"><small>Blocked:</small> <span
                                                    class="fs-6">{{ $blockedInstagramLinks }}</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ url('links/' . $youtubeId) }}">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="bi bi-youtube"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-2 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Youtube Links</p>
                                            <h6 class="card-title"><small>Active:</small> <span
                                                    class="fs-6">{{ $activeYoutubeLinks }}</span></h6>
                                            <h6 class="card-title"><small>Blocked:</small> <span
                                                    class="fs-6">{{ $blockedYoutubeLinks }}</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection