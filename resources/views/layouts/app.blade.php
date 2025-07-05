<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Bantuan Renovasi Rumah')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/home1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
<nav class="fixed top-0 left-0 w-full bg-[#018577] text-white py-2 px-5 z-50 shadow-md">
    <div class="container mx-auto flex items-center justify-between">
     <!-- Tombol Hamburger -->
        <button id="sidebarToggle" class="md:hidden bg-white text-[#018577] p-2 rounded-md focus:outline-none">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
            </svg>
        </button>    
    
    <!-- Logo -->
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16">
            <h1 class="text-xl font-bold hidden md:block">Portal Bantuan Renovasi Rumah</h1>
        </div>

        <!-- Button -->
        <a href="{{ route('login') }}" 
        class="bg-white text-[#018577] px-4 py-2 rounded-md font-semibold hover:bg-gray-200 transition">
            Masuk
        </a>
    </div>
</nav>

<!-- <nav class="fixed top-0 left-0 w-full bg-[#018577] text-white py-1 px-5 z-50 shadow-md">
    <div class="container mx-auto flex items-center justify-between">

        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16">
            <h1 class="text-xl font-bold">Portal Bantuan Renovasi Rumah</h1>
        </div> -->

        <!-- Simulasi User Login -->
        <!-- <div id="userSection" class="hidden relative">
            <div class="flex items-center space-x-2 cursor-pointer" onclick="toggleDropdown()">
                <span class="font-semibold" id="userName">Widya</span> 
                <img src="{{ asset('images/user-icon.svg') }}" class="h-8 w-8 rounded-full" alt="User Icon">
            </div>

            <div id="dropdownMenu" class="absolute right-0 mt-2 w-40 bg-white text-black rounded-md shadow-md hidden">
                <a href="#" class="block px-4 py-2 hover:bg-gray-200">Profile</a>
                <button onclick="fakeLogout()" class="w-full text-left px-4 py-2 hover:bg-gray-200">Log out</button>
            </div>
        </div>

        <a id="loginButton" href="#" onclick="fakeLogin()" class="bg-white text-[#018577] px-4 py-2 rounded-md font-semibold hover:bg-gray-200 transition">
            Masuk
        </a>
    </div>
</nav> -->


        <!-- Sidebar hanya ditampilkan jika halaman BUKAN dashboard -->
        @if (!request()->routeIs('dashboard'))
        <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-md p-4 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <div class="w-64 p-4 bg-white">
            <nav class="space-y-2 mt-16">
                <a href="{{ route('beranda') }}" 
                class="block py-2 px-4 rounded {{ request()->routeIs('beranda') ? 'bg-green-200 font-semibold' : 'text-black hover:bg-gray-100' }}">
                Beranda
                </a>

                <a href="{{ route('datakriteria') }}" 
                class="block py-2 px-4 rounded {{ request()->routeIs('datakriteria') ? 'bg-green-200 font-semibold' : 'text-black hover:bg-gray-100' }}">
                Data Kriteria
                </a>
                
                <a href="{{ route('datacalon') }}" 
                class="block py-2 px-4 rounded {{ request()->routeIs('datacalon') ? 'bg-green-200 font-semibold' : 'text-black hover:bg-gray-100' }}">
                Data Calon Penerima Bantuan
                </a>
                
                <a href="{{ route('perankingan') }}" 
                class="block py-2 px-4 rounded {{ request()->routeIs('perankingan') ? 'bg-green-200 font-semibold' : 'text-black hover:bg-gray-100' }}">
                Perankingan
                </a>
                
                <a href="{{ route('tambahakun') }}" 
                class="block py-2 px-4 rounded {{ request()->routeIs('tambahakun') ? 'bg-green-200 font-semibold' : 'text-black hover:bg-gray-100' }}">
                Tambah Akun
                </a>
            </nav>
            </div>

        @endif

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>
    </div>

    <footer class="bg-gray-800 text-white p-4 text-center mt-6">
        &copy; 2025 Portal Bantuan Renovasi Rumah
    </footer>
</body>

<script>
    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("hidden");
    }

    function fakeLogin() {
        // Simulasi user login
        localStorage.setItem("isLoggedIn", "true");
        updateNavbar();
    }

    function fakeLogout() {
        // Simulasi user logout
        localStorage.removeItem("isLoggedIn");
        updateNavbar();
    }

    function updateNavbar() {
        let isLoggedIn = localStorage.getItem("isLoggedIn");

        if (isLoggedIn) {
            document.getElementById("userSection").classList.remove("hidden");
            document.getElementById("loginButton").classList.add("hidden");
        } else {
            document.getElementById("userSection").classList.add("hidden");
            document.getElementById("loginButton").classList.remove("hidden");
        }
    }

    // Cek status login saat halaman dimuat
    document.addEventListener("DOMContentLoaded", updateNavbar);
</script>

<script>
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.getElementById("sidebarToggle");

    // Toggle sidebar saat tombol hamburger ditekan
    sidebarToggle.addEventListener("click", function () {
        sidebar.classList.toggle("-translate-x-full");
    });

    // Saat layar diperbesar lagi, sidebar harus otomatis muncul
    window.addEventListener("resize", function () {
        if (window.innerWidth >= 1500) {
            sidebar.classList.remove("-translate-x-full");
        } else {
            sidebar.classList.add("-translate-x-full");
        }
    });
</script>


</html>
