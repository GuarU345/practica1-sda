<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Registro</title>
</head>
<body class="flex justify-center items-center h-screen bg-gray-200">
    <form class="bg-white p-6 shadow-lg" action="{{ route('register') }}" method="POST">
        @csrf
        <h4 class="text-center mb-4">Registro</h4>

        @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Error!</strong>
                    <span class="block sm:inline">Por favor, corrige los siguientes errores:</span>
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
        @endif

        <div class="mb-4">
            <label class="text-sm font-medium text-gray-700" for="name">
                Nombre
                <input class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" id="name" name="name">
            </label>
        </div>

        <div class="mb-4">
            <label class="text-sm font-medium text-gray-700" for="email">
                Email
                <input class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" id="email" name="email">
            </label>
        </div>

        <div class="mb-4">
            <label class="text-sm font-medium text-gray-700" for="password">
                Contraseña
                <input class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" id="password" name="password">
            </label>
        </div>  

        <div class="g-recaptcha mt-2 w-full" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Registrarme</button>
        <a class="block text-center mt-4 text-blue-500 font-semibold" href="/login">Ya tienes cuenta, ve a iniciar sesión</a>
    </form>

    <!-- Script de Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js"></script>
</body>
</html>