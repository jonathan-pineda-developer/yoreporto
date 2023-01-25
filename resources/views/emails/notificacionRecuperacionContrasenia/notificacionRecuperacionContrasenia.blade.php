<html>
 <body>
 <h1>Recuperacion de contrasenia del Sistema Yo Reporto</h1>
    <p>Hola {{$user->nombre}} para reestablecer su contraseña acceda al siguiente link:</p>
    <a href="{{ url('recuperacionContrasenia/'.$user->id) }}">Recuperar contrasenia</a>
 </body>
</html>