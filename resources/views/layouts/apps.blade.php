<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Bantuan Renovasi Rumah Desa Sumberejo')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/home1.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    html, body {
        width: 100%;
        height: 100%;
        overflow-x: hidden;
    }
    .navbar-brand img {
        width: 60px;
    }
    .sidebar-link-active {
        background-color: #33B2A4;
        color: white !important;
        border-radius: 0.375rem;
    }

    .container {
        margin: 0;
        padding: 0;
    }
    .hero {
        width: 100%;
        height: 100vh;
        overflow: hidden;
        position: relative;
        margin: 0;
        padding: 0;
    }
    .hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hero::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0,0,0,0.4);
    }
    .hero-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        text-align: center;
        z-index: 3;
    }
    .text-justify {
    text-align: justify;
    }
    .transition-soft {
        transition: all 0.3s ease;
        border-color: #0dcaf0;
        color: #0dcaf0;
    }

    .transition-soft:hover {
        background-color: rgba(13, 202, 240, 0.05);
        box-shadow: 0 2px 6px rgba(13, 202, 240, 0.2);
        color: #0a9ecf;
        border-color: #0bbad0;
    }
</style>

</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-md navbar-dark fixed-top shadow ps-4" style="background-color: #018577; padding-top: 2px; padding-bottom: 2px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
            @if (!request()->routeIs('dashboard'))
                <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            @endif
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2">
                    <span class="d-none d-md-block">Portal Bantuan Rumah Tidak Layak Huni Desa Sumberejo</span>
                </a>
            </div>
            @if (request()->routeIs('dashboard'))
                <!-- Di dashboard, tombol Masuk -->
                <a href="{{ route('login') }}" class="btn btn-light text-success fw-semibold px-3 me-3" style="color: #018577">Masuk</a>
            @else
                <!-- Di halaman lain, ikon user -->
                <div class="dropdown me-4 d-flex align-items-center">
                 <!-- Tulisan Nama + Level, sembunyi kalau screen kecil -->
                @if(auth()->check())
                <div class="text-white me-3 d-none d-md-block text-end">
                    <div style="font-size: 1.3rem;">{{ auth()->user()->username }}</div>
                    <div style="font-size: 0.8rem;">{{ auth()->user()->level }}</div>
                </div>
                @endif


                <button class="border-0 bg-transparent p-0 m-0 d-flex align-items-center text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>

                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalProfile">Profile</a></li>
                        
                        <li>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item" style="border: none; background: none;">Log out</button>
                        </form>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </nav>


    <div class="d-flex mt-5 pt-4 ">
        @if (!request()->routeIs('dashboard'))
        <div class="offcanvas-md offcanvas-start bg-white shadow pt-3 px-3" id="sidebar" tabindex="-1" style="width: 260px; min-height: 100vh;">
            <div class="offcanvas-body">
                <nav class="nav flex-column gap-3">
                    <a href="{{ route('beranda') }}" class="nav-link py-2 px-3 {{ request()->routeIs('beranda') ? 'sidebar-link-active' : 'text-dark' }}">Beranda</a>
                    <a href="{{ route('datakriteria') }}" class="nav-link py-2 px-3 {{ request()->routeIs('datakriteria') ? 'sidebar-link-active' : 'text-dark' }}">Data Kriteria</a>
                    <a href="{{ route('datacalon') }}" class="nav-link py-2 px-3 {{ request()->routeIs('datacalon') ? 'sidebar-link-active' : 'text-dark' }}">Data Calon Penerima Bantuan</a>
                    <a href="{{ route('perankingan') }}" class="nav-link py-2 px-3 {{ request()->routeIs('perankingan') ? 'sidebar-link-active' : 'text-dark' }}">Perankingan</a>
                    <a href="{{ route('penerima') }}" class="nav-link py-2 px-3 {{ request()->routeIs('penerima') ? 'sidebar-link-active' : 'text-dark' }}">Data Penerima Yang Diajukan & Disetujui</a>
                    @if(auth()->user()->level === 'Admin')
                        <a href="{{ route('tambahakun') }}" class="nav-link py-2 px-3 {{ request()->routeIs('tambahakun') ? 'sidebar-link-active' : 'text-dark' }}">
                            Tambah Akun Petugas
                        </a>
                    @endif
                </nav>
            </div>
        </div>
        @endif

        <main class="flex-grow-1 w-100 mt-0 pt-0 position-relative">
            @yield('content')

            @yield('scripts')
        </main>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        &copy; 2025 Portal Bantuan Renovasi Rumah
    </footer>

    @include('partial.modalprofil')

    @if ($errors->any() && old('modal_profile'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('modalProfile'));
            modal.show();
        });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>