<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Usuario</h1>
    <!-- errores -->
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form action="{{  Route('usuario.registrar')}}" method="POST">
        @csrf
        <input type="text" name="nombre" placeholder="Ingresar Nombre"> <br>
        <input type="text" name="email" placeholder="Ingresar Email"> <br>
        <input type="password" name="password" placeholder="Ingrese Contraseña"> <br>
        <input type="password" name="rePassword" placeholder="Ingrese Nuevamente su Contraseña"> <br>
        <input type="text" name="dayCode" placeholder="Ingrese código del día"> <br>
        <button type="submit">Ingresar</button>
    </form>
    <p>Si usted tiene una cuenta, entonces <a href="/login">inicie sesión</a></p>
</body>
</html>
