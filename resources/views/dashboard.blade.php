<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body>
    <div class="flex flex-col mt-4 p-6">
        <form class="self-end"  action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 p-4 rounded ml-auto">Cerrar Sesión</button>
        </form>
        <p>Hello World</p>
    </div>
</body>
</html>