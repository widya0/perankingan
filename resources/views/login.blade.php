<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .split-screen {
            display: flex;
            height: 100vh;
        }

        .left-side {
            flex: 1;
            background: url('images/home1.png') no-repeat center center;
            background-size: cover;
        }

        .right-side {
            flex: 1;
            background-color: #eaf4f2;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 50px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #198754;
        }

        .btn-login {
            background-color: #0c7a6f;
            color: white;
        }

        .btn-login:hover {
            background-color: #075b52;
        }

        @media(max-width: 768px) {
            .left-side {
                display: none;
            }
            .right-side {
                flex: 1;
            }
        }
    </style>
</head>

<body>

    <div class="split-screen">
        <!-- Left Image -->
        <div class="left-side"></div>

        <!-- Right Form -->
        <div class="right-side">
            <div class="d-flex mb-4 align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="80" class="me-2">
                <h3 class="fw-bold m-0">Portal Bantuan Rumah Tidak Layak Huni Sumberejo</h3>
            </div>
           
            <p class="fw-semibold mt-5">Silahkan Masukkan Username dan Password untuk Masuk</p>
            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
                        <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                            <i id="eye-icon" class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <button type="submit" class="btn btn-login w-100">LOGIN</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            }
        }
    </script>

</body>

</html>
