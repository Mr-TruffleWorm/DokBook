<!DOCTYPE html>
<html lang="en">
<head>
    
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard - Online Clinic')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 font-sans antialiased">
    @include('layouts.header')
    @include('admin.sidenav')
    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Content -->
      <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">
          @yield('content')
        </div>
      </main>
    </div>

</body>
</html>
