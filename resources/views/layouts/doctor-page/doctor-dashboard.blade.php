<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Doctor Dashboard</title>
</head>
<body>
    <header>
        @include('layouts.header')
    </header>
    <main>
        @yield('maincontent')
    </main>
</body>
</html>