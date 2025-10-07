<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Doctor Dashboard</title>
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <!-- Main Wrapper -->
    <div class="flex flex-col min-h-screen">

        <!-- Header (Top Section) -->
        <header class="w-full bg-white shadow z-10">
            @include('layouts.header')
        </header>

        <!-- Content Area (Sidebar + Main Content) -->
        <div class="flex flex-1 overflow-hidden">

            <!-- Sidebar (Left) -->
            <aside
                class="bg-white w-64 border-r border-gray-200 flex-shrink-0 hidden lg:block"
            >
                @include('layouts.doctor-page.sidebarnav')
            </aside>

            <!-- Mobile Sidebar (Toggle with Alpine.js) -->
            <div
                class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 lg:hidden"
                x-show="sidebarOpen"
                @click="sidebarOpen = false"
                x-transition.opacity
            ></div>

            <aside
                class="fixed inset-y-0 left-0 w-64 bg-white z-30 border-r border-gray-200 transform lg:hidden"
                x-show="sidebarOpen"
                x-transition
            >
                @include('layouts.doctor-page.sidebarnav')
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
