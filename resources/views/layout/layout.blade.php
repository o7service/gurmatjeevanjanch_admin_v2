@if(!Auth::check() || !Auth::user()->email)
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Gurmat Jeevan Jaach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}" />
    <style>
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 1055;
            text-align: center;
            padding-top: 20%;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 26px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        /* When checked */
        .toggle-switch input:checked+.toggle-slider {
            background-color: #0d6efd;
            /* green */
        }

        /* Move circle when checked */
        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        /* Optional: add a shadow for better look */
        .toggle-slider.round {
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="index.html" class="logo">
                        <img src="{{ url('img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                            height="20" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content d-flex flex-column h-100">
                    <ul class="nav nav-secondary flex-grow-1" id="sidebarList">
                        <li class="nav-item active">
                            <a href="{{ route('dashboard')}}" aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Components</h4>
                        </li>




                    </ul>
                    <!-- Logout Button !-->
                    <!-- <ul class="nav nav-secondary mt-auto">
                        <li class="nav-item">
                            <a href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul> -->
                </div>
            </div>

        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <!-- <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </nav> -->

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                    aria-expanded="false" aria-haspopup="true">
                                    <i class="fa fa-search"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input type="text" placeholder="Search ..." class="form-control" />
                                        </div>
                                    </form>
                                </ul>
                            </li>

                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="img/profile.jpg" alt="image profile"
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                </div>
                                            </div>
                                        </li>


                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </li>


                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div id="loader">
                <div class="spinner-border  text-primary"> </div>
            </div>

            @yield('content')

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-center">

                    <!-- <div class="copyright">
                        2025, made with <i class="fa fa-heart heart text-danger"></i>
                    </div> -->
                    <div>
                        Developed by
                        <a target="_blank" href="https://o7solutions.in/">O7SOLUTIONS</a>.
                    </div>
                </div>
            </footer>
        </div>







        <ul class="nav nav-secondary flex-grow-1" id="sidebarList">
            <li class="nav-item active">
                <a href="{{ route('dashboard') }}" aria-expanded="false">
                    <i class="fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-section">
                <span class="sidebar-mini-icon">
                    <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Components</h4>
            </li>

            <!-- Categories will render here -->
        </ul>

        <script>
            async function getCategories() {
                try {
                    const response = await fetch('/layout/category');
                    if (!response.ok) throw new Error("Network response was not ok");
                    const categories = await response.json();
                    console.log(categories)

                    const sidebarList = document.getElementById('sidebarList');

                    // Loop through categories
                    categories.forEach(cat => {
                        const li = document.createElement('li');
                        li.classList.add('nav-item');

                        li.innerHTML = `
                    <a href="/links/${cat.id}">
                        <img src="/categories/${cat.icon}" 
                             style="height:20px;width:20px;margin-right:8px; border-radius:50%"; />
                        <p>${cat.title}</p>
                    </a>
                `;
                        sidebarList.appendChild(li);
                    });

                    const staticLinks = [
                        { title: 'Singer', route: '/singer' },   // replace with actual route
                        { title: 'Program', route: '/programs' } // replace with actual route
                    ];

                    staticLinks.forEach(link => {
                        const li = document.createElement('li');
                        li.classList.add('nav-item');
                        li.innerHTML = `
                <a href="${link.route}">
                    <i class="bi bi-mic"></i>
                    <p>${link.title}</p>
                </a>
            `;
                        sidebarList.appendChild(li);
                    });

                } catch (error) {
                    console.error("Error fetching categories:", error);
                }
            }

            // Call on page load
            document.addEventListener('DOMContentLoaded', getCategories);
        </script>




        <script>

            function showSpinner() {
                document.getElementById('loader').style.display = 'block';
            }
            function hideSpinner() {
                document.getElementById('loader').style.display = 'none';
            }

            function showToastr(msg, title) {
                var placementFrom = $("#notify_placement_from option:selected").val();
                var placementAlign = $("#notify_placement_align option:selected").val();
                var state = $("#notify_state option:selected").val();
                var content = {};
                content.message = msg;
                content.title = title;
                content.icon = title === "Success" ? "fa fa-check" : "none";
                var notify = $.notify(content, {
                    type: state,
                    placement: {
                        from: placementFrom,
                        align: placementAlign,
                    },
                    time: 500,
                    delay: 20000,
                    template:
                        '<div data-notify="container" onclick="$(this).remove()" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert" style="max-width: 300px;">' +
                        '<span data-notify="icon"></span> ' +
                        '<span data-notify="title">{1}</span> ' +
                        '<span data-notify="message">{2}</span>' +
                        '</div>',
                });
            }
        </script>


        <script src="{{ asset('js/core/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('js/core/popper.min.js') }}"></script>
        <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
        <script src="{{ asset('js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
        <script src="{{ asset('js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
        <script src="{{ asset('js/plugin/jsvectormap/world.js') }}"></script>
        <script src="{{ asset('js/plugin/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ asset('js/kaiadmin.min.js') }}"></script>

</body>

</html>