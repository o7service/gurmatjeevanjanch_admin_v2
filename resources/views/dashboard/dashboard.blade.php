@extends('layout.layout')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Navigate
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route("telegram.index") }}">Telegram Links</a></li>
                            <li><a class="dropdown-item" href="{{ route("facebook.index") }}">Facebook Links</a></li>
                            <li><a class="dropdown-item" href="{{ route("whatsappGroups.index") }}">Whatsapp Groups
                                    Links</a></li>
                            <li><a class="dropdown-item" href="{{ route("instagram.index") }}">Instagram Links</a></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="bi bi-telegram"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <a href="{{ route('telegram.index') }}">
                                            <p class="card-category">Telegram Links</p>
                                            <h6 class="card-title"><small>Active:</small> <span
                                                    class="fs-5">{{ $activeTelegramLinks }}</span></h6>
                                            <h6 class="card-title"><small>Blocked:</small> <span
                                                    class="fs-5">{{ $blockedTelegramLinks }}</span></h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="bi bi-facebook"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <a href="{{ route('facebook.index') }}">
                                            <p class="card-category">Facebook Links</p>
                                            <h6 class="card-title"><small>Active:</small> <span
                                                    class="fs-5">{{ $activeFacebookLinks }}</span></h6>
                                            <h6 class="card-title"><small>Blocked:</small> <span
                                                    class="fs-5">{{ $blockedFacebookLinks }}</span></h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <a href="{{ route('whatsappGroups.index') }}">
                                            <p class="card-category">Whatsapp Links</p>
                                            <h6 class="card-title"><small>Active:</small> <span
                                                    class="fs-5">{{ $activeWhatsappGroupLinks }}</span></h6>
                                            <h6 class="card-title"><small>Blocked:</small> <span
                                                    class="fs-5">{{ $blockedWhatsappGroupLinks }}</span></h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="bi bi-instagram"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <a href="{{ route('instagram.index') }}">
                                        <p class="card-category">Instagram Links</p>
                                        <h6 class="card-title"><small>Active:</small> <span
                                                class="fs-5">{{ $activeInstagramLinks }}</span></h6>
                                        <h6 class="card-title"><small>Blocked:</small> <span
                                                class="fs-5">{{ $blockedInstagramLinks }}</span></h6>
                                                </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection