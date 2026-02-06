<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100">

    <div class="min-h-screen">
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>