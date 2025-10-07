<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
    <!-- Sidebar (Fixed on the left) -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0"
        :class="{'translate-x-0': sidebarOpen}"
    >
        <!-- Brand -->
        <div class="flex items-center justify-center h-16 bg-blue-700">
            <h1 class="text-xl font-bold">Doctor Dashboard</h1>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 px-2 space-y-1 text-sm overflow-y-auto h-[calc(100vh-4rem)]">
            <a href="{{ route('doctor.dashboard') }}"
               class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('doctor.dashboard') ? 'bg-gray-800' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                My Dashboard
            </a>

            <a href="{{ route('doctor.schedule') }}"
               class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('doctor.schedule') ? 'bg-gray-800' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                My Available Schedules
            </a>

            <a href="#"
               class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Appointment Requests
            </a>

            <a href="{{ route('doctor.patients') }}"
               class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('doctor.patients') ? 'bg-gray-800' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                View Patients
            </a>

            <a href="{{ route('doctor.reports') }}"
               class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('doctor.reports') ? 'bg-gray-800' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Reports
            </a>
        </nav>
    </aside>

    <!-- Overlay (for mobile view) -->
    <div
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition
    ></div>

    <!-- Main Content (Scrollable) -->
    <div class="flex-1 ml-0 lg:ml-64 bg-gray-100 overflow-y-auto h-screen p-6">
        <h1 class="text-2xl font-bold mb-6">Welcome, Doctor</h1>

        <!-- Example content area -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <p>This is the main content section. Scroll this area down while the sidebar stays fixed.</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 h-[1000px]">
                <p>Scroll test content...</p>
            </div>
        </div>
    </div>
</div>
