<html>
 <body>
 <h1>Bienvenido al sistema Yo Reporto</h1>
 <p>Usted ha sido agregado al sistema con el rol: {{ $user->rol }}</p>
 <p>Para ingresar al sistema debe ingresar a la siguiente dirección: <a href="http://localhost:8000/login">http://localhost:8000/login</a></p>
    <p>Su usuario es: {{ $user->email }}</p>
    <p>La contraseña es: {{ $password }}</p>
 </body>
</html>
