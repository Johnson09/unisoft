<!DOCTYPE html>
<html lang="en">
<head>
	<title>Unisoft</title>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Script -->
    <!-- <script src="{{ asset('public/js/app.js') }}" defer></script> -->
    <script src="{{ secure_asset('public/js/style.js') }}" ></script>
    <link rel="icon" type="image/png" href="{{ secure_asset('public/images/icons/favicon.ico') }}"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Style -->
    <!-- <link rel="stylesheet" href="{{ asset('public/css/app.css') }}"> -->
    <link rel="stylesheet" href="{{ secure_asset('public/css/style.css') }}" media="all" rel="stylesheet" type="text/css">

    <script>
        // Mensaje de advertencia o error de logueo
        function vali(){
          var code = document.getElementById('codigo').value;
          swal({
            title: code,
            text: 'Verifica que la contraseña o el usuario esten bien escritos.',
            icon: "error",
            buttons: "Aceptar!",
          });
        }
    </script>
</head>
<body>
    @if(session()->has('status'))
      <input type="hidden" id="codigo" value="{{ session()->get('status') }}">
      <?php
      echo "<script>";
      echo "vali();";
      echo "</script>";
       ?>
    @endif
	
    <div class="page">
        <div class="container">
            <div class="left">
            <div class="login">Login</div>
            <div class="eula">By logging in you agree to the ridiculously long terms that you didn't bother to read</div>
            </div>
            <div class="right">
            <svg viewBox="0 0 320 300">
                    <defs>
                    <linearGradient
                                    inkscape:collect="always"
                                    id="linearGradient"
                                    x1="13"
                                    y1="193.49992"
                                    x2="307"
                                    y2="193.49992"
                                    gradientUnits="userSpaceOnUse">
                        <stop
                            style="stop-color:#ff00ff;"
                            offset="0"
                            id="stop876" />
                        <stop
                            style="stop-color:#ff0000;"
                            offset="1"
                            id="stop878" />
                    </linearGradient>
                    </defs>
                    <path d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143" />
                </svg>
                <div class="form">
                    <form action="{{ url('login') }}" method="post">
                    <!-- Formulario para login -->
                    @csrf
                        <label for="email">Usuario</label>
                        <input type="text" id="email" name="usuario" required="required" autocomplete="off">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="contraseña" required="required">
                        <input type="submit" id="submit" value="Ingresar">
                    </form>
            </div>
            </div>
        </div>
    </div>

</body>
</html>