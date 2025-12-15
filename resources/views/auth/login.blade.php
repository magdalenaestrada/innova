<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>

    <div class="wrapper">
        <form class="form" method="POST" action="{{ route('login') }}">
            @csrf
            <h1>{{ config('app.name') }}</h1>

            <div class="input-box">
                <input id="email" type="email" name="email" placeholder="Correo" aria-label="Correo electrónico"
                    required autocomplete="email" autofocus>

                <i class="bx bxs-user"></i>
            </div>

            <div class="input-box">
                <input id="password" type="password" name="password" placeholder="Contraseña" required
                    autocomplete="current-password">
                <i class="bx bxs-lock-alt"></i>
            </div>



            <button type="submit" class="btn">Iniciar sesión</button>



        </form>
    </div>















   
    <script>
        const form = document.querySelector('.form');
        const button = document.querySelector('.btn');
    
        form.addEventListener('submit', function(event) {
            // Disable the button to prevent multiple submissions
            button.disabled = true;
            button.textContent = 'Procesando...'; // Provide feedback to the user
        });
    </script>
    



</body>

</html>
