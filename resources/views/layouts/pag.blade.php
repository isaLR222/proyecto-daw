<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi App')</title>
    <script src="https://unpkg.com/vue@3"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-fondo text-white flex flex-col">
    <x-header />

    <main class="max-w-7xl mx-auto px-6 py-8 flex-grow">
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
