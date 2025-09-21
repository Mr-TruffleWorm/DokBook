<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" 
           :class="{'translate-x-0': sidebarOpen}">
      
      <!-- Brand -->
      <div class="flex items-center justify-center h-16 bg-blue-700">
        <h1 class="text-xl font-bold">Clinic Admin</h1>
      </div>

      <!-- Nav -->
      <nav class="mt-6 px-2 space-y-1 text-sm" x-data="{ openMenu: '' }">
        
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
          </svg>
          Dashboard
        </a>
        <a href="{{ route('admin.add-service') }}"
           class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
          </svg>
          Create New Service
        </a>

        <!-- Doctor Account Management -->
        <div>
          <button @click="openMenu === 'doctor' ? openMenu='' : openMenu='doctor'"
                  class="flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-gray-800">
            <span class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Doctor Account Management
            </span>
            <svg class="w-4 h-4" :class="openMenu==='doctor' && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div x-show="openMenu==='doctor'" class="pl-10 space-y-1" x-cloak>
            <a href="{{ route('admin.create-doctor') }}" class="block py-2 px-3 rounded hover:bg-gray-800">Add New Doctor</a>
            <a href="{{ route('admin.list-doctors') }}" class="block py-2 px-3 rounded hover:bg-gray-800">View Doctors</a>
          </div>
        </div>

        <!-- User Account Management -->
        <div>
          <button @click="openMenu === 'users' ? openMenu='' : openMenu='users'"
                  class="flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-gray-800">
            <span class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
              </svg>
              User Account Management
            </span>
            <svg class="w-4 h-4" :class="openMenu==='users' && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div x-show="openMenu==='users'" class="pl-10 space-y-1" x-cloak>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">View User Appointments</a>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800"> Appointments</a>
          </div>
        </div>

        <!-- Bookings -->
        <div>
          <button @click="openMenu === 'bookings' ? openMenu='' : openMenu='bookings'"
                  class="flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-gray-800">
            <span class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              Bookings
            </span>
            <svg class="w-4 h-4" :class="openMenu==='bookings' && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div x-show="openMenu==='bookings'" class="pl-10 space-y-1" x-cloak>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Change Booking Dates</a>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Manage Appointment Bookings</a>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Booking Link 3</a>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Booking Link 4</a>
          </div>
        </div>

        <!-- Logs -->
        <div>
          <button @click="openMenu === 'logs' ? openMenu='' : openMenu='logs'"
                  class="flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-gray-800">
            <span class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Logs
            </span>
            <svg class="w-4 h-4" :class="openMenu==='logs' && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div x-show="openMenu==='logs'" class="pl-10 space-y-1" x-cloak>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Log Link 1</a>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Log Link 2</a>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Log Link 3</a>
            <a href="#" class="block py-2 px-3 rounded hover:bg-gray-800">Log Link 4</a>
          </div>
        </div>
        <a href="#"
           class="flex items-center px-4 py-2 rounded-md hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
          </svg>
          Settings
        </a>
      </nav>
    </aside>

    <!-- Overlay for mobile -->
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" 
         x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition></div>