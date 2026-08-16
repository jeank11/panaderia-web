<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Acceso Administrativo - PanaEcheveste</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;

            font-family: Arial, Helvetica, sans-serif;

            background:
                linear-gradient(
                    rgba(67, 42, 24, 0.82),
                    rgba(67, 42, 24, 0.82)
                ),
                url("https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1920&q=80");

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 430px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.97);

            border-radius: 20px;

            padding: 40px 35px;

            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.35);

            text-align: center;
        }

        .logo {
            width: 85px;
            height: 85px;

            margin: 0 auto 15px;

            border-radius: 50%;

            background: #f5e6d3;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 42px;

            box-shadow:
                0 5px 15px rgba(0, 0, 0, 0.12);
        }

        .brand {
            margin: 0;

            font-size: 28px;

            font-weight: bold;

            color: #5b371f;
        }

        .subtitle {
            margin-top: 7px;
            margin-bottom: 30px;

            color: #777;

            font-size: 14px;
        }

        .form-group {
            text-align: left;

            margin-bottom: 20px;
        }

        .form-label {
            display: block;

            margin-bottom: 8px;

            font-size: 14px;

            font-weight: bold;

            color: #4b3424;
        }

        .form-input {
            width: 100%;

            padding: 13px 15px;

            border: 1px solid #ddd;

            border-radius: 10px;

            font-size: 15px;

            outline: none;

            transition: 0.2s;
        }

        .form-input:focus {
            border-color: #a66a3f;

            box-shadow:
                0 0 0 3px rgba(166, 106, 63, 0.15);
        }

        .remember {
            display: flex;

            align-items: center;

            gap: 8px;

            margin-bottom: 25px;

            text-align: left;

            font-size: 14px;

            color: #666;
        }

        .remember input {
            width: 16px;
            height: 16px;
        }

        .login-button {
            width: 100%;

            border: none;

            padding: 14px;

            border-radius: 10px;

            background: #8b542f;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;
        }

        .login-button:hover {
            background: #6f4023;

            transform: translateY(-1px);
        }

        .forgot-password {
            display: block;

            margin-top: 18px;

            color: #8b542f;

            font-size: 14px;

            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #f8d7da;

            color: #842029;

            border: 1px solid #f1aeb5;

            padding: 10px 12px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;

            text-align: left;
        }

        .status-message {
            background: #d1e7dd;

            color: #0f5132;

            padding: 10px 12px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;

            text-align: left;
        }

        .footer {
            margin-top: 25px;

            padding-top: 20px;

            border-top: 1px solid #eee;

            font-size: 12px;

            color: #999;
        }

        .home-link {
            display: inline-block;

            margin-top: 8px;

            color: #8b542f;

            text-decoration: none;

            font-size: 13px;
        }

        .home-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {

            .login-container {
                padding: 15px;
            }

            .login-card {
                padding: 30px 22px;
            }

            .brand {
                font-size: 24px;
            }

        }

    </style>

</head>


<body>

    <div class="login-container">

        <div class="login-card">


            <!-- LOGO -->

            <div class="logo">
                🥖
            </div>


            <!-- MARCA -->

            <h1 class="brand">
                PanaEcheveste
            </h1>


            <p class="subtitle">
                Panel de administración
            </p>


            <!-- MENSAJE DE SESIÓN -->

            @if (session('status'))

                <div class="status-message">

                    {{ session('status') }}

                </div>

            @endif


            <!-- ERRORES -->

            @if ($errors->any())

                <div class="error-message">

                    {{ $errors->first() }}

                </div>

            @endif


            <!-- FORMULARIO -->

            <form method="POST" action="{{ route('login') }}">

                @csrf


                <!-- EMAIL -->

                <div class="form-group">

                    <label
                        for="email"
                        class="form-label"
                    >
                        📧 Correo electrónico
                    </label>


                    <input
                        id="email"
                        class="form-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Ingrese su correo"
                    >

                </div>


                <!-- CONTRASEÑA -->

                <div class="form-group">

                    <label
                        for="password"
                        class="form-label"
                    >
                        🔐 Contraseña
                    </label>


                    <input
                        id="password"
                        class="form-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Ingrese su contraseña"
                    >

                </div>


                <!-- RECORDAR -->

                <div class="remember">

                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                    >

                    <label for="remember_me">
                        Recordarme
                    </label>

                </div>


                <!-- BOTÓN -->

                <button
                    type="submit"
                    class="login-button"
                >
                    🔓 Iniciar sesión
                </button>


                <!-- RECUPERAR CONTRASEÑA -->

                @if (Route::has('password.request'))

                    <a
                        class="forgot-password"
                        href="{{ route('password.request') }}"
                    >
                        ¿Olvidaste tu contraseña?
                    </a>

                @endif

            </form>


            <!-- PIE -->

            <div class="footer">

                Acceso exclusivo para personal autorizado.

                <br>

                <a
                    href="{{ url('/') }}"
                    class="home-link"
                >
                    ← Volver a la página principal
                </a>

            </div>


        </div>

    </div>

</body>

</html>

