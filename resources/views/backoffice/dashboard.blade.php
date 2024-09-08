<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard del backoffice</title>
</head>
<body>
    {{$user}}
    <hr>
    <p>Nombre: {{$user->nombre}}</p>
    <p>Email: {{$user->email}}</p>
    <form action="{{ route('usuario.logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form>
</body>
</html>