<!-- Universal Header -->
<header class="bg-white shadow-sm border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700">
  <div class="mx-auto max-w-screen-xl">
    
    <!-- Main Header Bar -->
    <div class="flex items-center justify-between h-16 px-4">
      
      <!-- Left Section: Mobile Toggle + Logo -->
      <div class="flex items-center space-x-3">
        <!-- Mobile toggle (only shown for authenticated users with sidebar) -->
        @auth
          <button @click="sidebarOpen = !sidebarOpen" 
                  class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-gray-100 dark:hover:bg-gray-800">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        @endauth

        <!-- Logo -->
        <a href="/" class="flex items-center space-x-3">
          <img src="{{ asset('images/medical-team.png') }}" class="h-8" alt="DokBook Logo" />
          <span class="text-2xl font-semibold whitespace-nowrap text-teal-600 hover:text-teal-800 dark:text-white">
            DokBook
          </span>
        </a>
      </div>

      <!-- Center Section: Page Title (only for authenticated users) -->
      @auth
        <div class="hidden md:block">
          <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            @yield('page-title', 'Dashboard')
          </h1>
        </div>
      @endauth

      <!-- Right Section: Contact + Actions -->
      <div class="flex items-center space-x-4">
        @auth
          <!-- Notifications -->
          <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-gray-100 dark:hover:bg-gray-800 rounded-full">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3-3v-2a7 7 0 10-14 0v2l-3 3h5m9 0a3 3 0 11-6 0m6 0H6"/>
            </svg>
            <!-- Notification badge (optional) -->
            <span class="sr-only">View notifications</span>
          </button>

          <!-- Profile Dropdown -->
          <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" 
                    class="flex items-center space-x-2 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
              <img class="h-8 w-8 rounded-full object-cover" 
                   src="https://i.pravatar.cc/100?u={{ auth()->user()->email ?? 'default' }}" 
                   alt="{{ auth()->user()->name ?? 'User' }}">
              <div class="hidden md:block text-left">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                  {{ auth()->user()->name ?? 'User' }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                  {{ auth()->user()->email ?? '' }}
                </div>
              </div>
              <svg class="w-4 h-4 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="dropdownOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 @click.outside="dropdownOpen = false"
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-50">
              
              <!-- User Info (mobile) -->
              <div class="md:hidden px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                  {{ auth()->user()->name ?? 'User' }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                  {{ auth()->user()->email ?? '' }}
                </div>
              </div>

              <!-- Menu Items -->
              <div class="py-1">
                <a href="{{ url('dashboard') }}" 
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                  <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                  </svg>
                  Dashboard
                </a>
                <a href="#" 
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                  <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Profile
                </a>
                <a href="#" 
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                  <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Settings
                </a>
              </div>
              
              <!-- Logout -->
              <div class="border-t border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" 
                          class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign out
                  </button>
                </form>
              </div>
            </div>
          </div>

        @else
          <!-- Unauthenticated User Actions -->
          <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" 
               class="text-sm text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition-colors">
              <span class="material-symbols-outlined">person</span>
            </a>
          </div>
        @endauth
      </div>
    </div>

    <!-- Mobile Page Title (for authenticated users) -->
    @auth
      <div class="md:hidden px-4 py-2 border-t border-gray-200 dark:border-gray-700">
        <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
          @yield('page-title', 'Dashboard')
        </h1>
      </div>
    @endauth

  </div>
</header>