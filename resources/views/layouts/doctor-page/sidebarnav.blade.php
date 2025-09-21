<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col">
        <div class="px-6 py-4 bg-blue-600 text-white font-bold text-lg">
            Doctor Dashboard
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 text-gray-700">
            <a href="{{ route('doctor.appointments') }}" class="block px-4 py-2 rounded hover:bg-blue-100">
                📅 Appointment Requests
            </a>
            <a href="{{ route('doctor.schedule') }}" class="block px-4 py-2 rounded hover:bg-blue-100">
                ⏰ Manage Schedule
            </a>
            <a href="{{ route('doctor.patients') }}" class="block px-4 py-2 rounded hover:bg-blue-100">
                🧑‍🤝‍🧑 My Patients
            </a>
            <a href="{{ route('doctor.reports') }}" class="block px-4 py-2 rounded hover:bg-blue-100">
                📊 Reports
            </a>
        </nav>
        <div class="px-4 py-4 border-t">
            <a href="{{ route('logout') }}" class="block w-full text-center bg-red-500 text-white py-2 rounded hover:bg-red-600">
                🚪 Logout
            </a>
        </div>
    </aside>