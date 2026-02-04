<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ isset($title) ? $title . ' | ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>
  <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
  @filamentStyles
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-gray-400 font-sans antialiased" x-data="{ sidebarOpen: false }">

  <div class="flex h-screen overflow-hidden">

    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-cloak
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
      class="fixed inset-0 z-40 bg-black/50 transition-opacity lg:hidden" aria-hidden="true">
    </div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak
      class="fixed inset-y-0 left-0 z-1 w-64 bg-white border-r border-gray-200 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0">

      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <span class="text-xl font-bold text-amber-500">DRDI <span class="text-gray-800">NCST</span></span>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500">
          <x-heroicon-o-x-mark class="w-6 h-6" />
        </button>
      </div>

      <nav class="mt-6 px-4 space-y-1">
        <a href="{{ route('teacher.home') }}" wire:navigate
          class="flex items-center px-4 py-2 rounded-lg group {{ request()->routeIs('teacher.home') ? 'text-blue-600 bg-blue-200' : 'hover:bg-gray-100 transition-colors' }}">
          <x-heroicon-o-home class="w-5 h-5 mr-3" /> Home
        </a>
        <a href="{{ route('teacher.my-sections') }}" wire:navigate
          class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('teacher.my-sections', 'teacher.my-sections.*') ? 'text-blue-600 bg-blue-200' : 'hover:bg-gray-100' }}">
          <x-heroicon-o-squares-2x2 class="w-5 h-5 mr-3" /> Sections
        </a>
        <a href="#"
          class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('teacher.analytics') ? 'text-blue-600 bg-blue-200' : 'hover:bg-gray-100' }}">
          <x-heroicon-o-chart-bar class="w-5 h-5 mr-3" /> Analytics
        </a>
      </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
      <header
        class="bg-sky-800 border-b text-white border-gray-200 h-16 flex items-center justify-between px-4 lg:px-8">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-white">
          <x-heroicon-o-bars-3 class="w-6 h-6" />
        </button>

        <div class="flex-1"></div>
        <div class="flex items-center space-x-4">
          {{-- <button class="hover:text-gray-200"><x-heroicon-o-squares-2x2 class="w-6 h-6" /></button> --}}
          <div class="relative">
            <button class="hover:text-gray-200"><x-heroicon-o-bell class="w-6 h-6" /></button>
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
          </div>

          <div class="relative" x-data="{ dropdownOpen: false }">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
              <img src="https://ui-avatars.com/api/?name=User"
                class="h-8 w-8 rounded-full border-2 border-white hover:border-gray-300 transition-colors" alt="Avatar">
              <x-heroicon-o-chevron-down class="w-4 h-4 transition-transform"
                ::class="dropdownOpen ? 'rotate-180' : ''" />
            </button>

            <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
              x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
              class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200" x-cloak>

              <div class="px-4 py-2 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
              </div>

              <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <x-heroicon-o-user class="w-4 h-4 mr-3" />
                Profile
              </a>

              <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <x-heroicon-o-cog-6-tooth class="w-4 h-4 mr-3" />
                Settings
              </a>

              <div class="border-t border-gray-100"></div>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                  class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                  <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4 mr-3" />
                  Logout
                </button>
              </form>
            </div>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 lg:p-4">
        {{ $slot }}
      </main>
    </div>
  </div>
  @livewire('notifications')
  @livewireScripts
  @filamentScripts
</body>

</html>