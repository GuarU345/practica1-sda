<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Inicio de Sesión</title>
</head>
<body class="flex justify-center items-center h-screen bg-gray-200">
    <form class="bg-white p-6 shadow-lg" action="{{ route('login') }}" method="POST">
        @csrf
        <h4 class="text-center mb-4">Inicio de Sesión</h4>
        <div class="mb-4">
            <label for="email" class="text-sm font-medium text-gray-700">Correo electrónico</label>
            <input type="email" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="email" required value="{{ old('email') }}">
            @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" name="password" required>
            @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Iniciar sesión</button>
        <a class="text-center mt-4 text-blue-500 font-semibold" href="/register">No tienes cuenta, registrate</a>
    </form>
</body>
</html>