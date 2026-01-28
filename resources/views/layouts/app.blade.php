<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>

    <div x-data="{ sidebarIsOpen: false }" class="relative flex w-full flex-col md:flex-row">
        <!-- This allows screen readers to skip the sidebar and go directly to the main content. -->
        <a class="sr-only" href="#main-content">skip to the main content</a>

        <!-- dark overlay for when the sidebar is open on smaller screens  -->
        <div x-cloak x-show="sidebarIsOpen" class="fixed inset-0 z-20 bg-surface-dark/10 backdrop-blur-xs md:hidden"
            aria-hidden="true" x-on:click="sidebarIsOpen = false" x-transition.opacity></div>

        <nav x-cloak
            class="fixed left-0 z-30 flex h-svh w-60 shrink-0 flex-col border-r border-outline bg-surface-alt p-4 transition-transform duration-300 md:w-64 md:translate-x-0 md:relative dark:border-outline-dark dark:bg-surface-dark-alt"
            x-bind:class="sidebarIsOpen ? 'translate-x-0' : '-translate-x-60'" aria-label="sidebar navigation">
            <!-- logo  -->
            <a href="#"
                class="mx-auto ml-2 w-fit text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                <span class="sr-only">homepage</span>
                <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-10 w-10 inline" /> <span
                    class="">DRDI</span>
            </a>

            <!-- search  -->
            {{-- <div class="relative my-4 flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
                    stroke-width="2"
                    class="absolute left-2 top-1/2 size-5 -translate-y-1/2 text-on-surface/50 dark:text-on-surface-dark/50"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="search"
                    class="w-full border border-outline rounded-radius bg-surface px-2 py-1.5 pl-9 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark/50 dark:focus-visible:outline-primary-dark"
                    name="search" aria-label="Search" placeholder="Search" />
            </div> --}}

            <!-- sidebar links  -->
            <div class="flex flex-col gap-2 overflow-y-auto pb-6 mt-10">

                @php
                    $userType = Auth::user()->profileable_type;
                    $prefix = $userType === \App\Models\Teacher::class ? 'teacher' : 'student';
                @endphp

                @if ($userType === \App\Models\Teacher::class)
                    <a href="{{ route($prefix . '.home') }}" wire:navigate
                        class="flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium {{ request()->routeIs($prefix . '.home') ? 'bg-primary/80 text-on-primary dark:bg-primary-dark/10 dark:text-on-surface-dark-strong' : 'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong' }} underline-offset-2 focus-visible:underline focus:outline-hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-5 shrink-0" aria-hidden="true">
                            <path
                                d="M10.707 2.293a1 1 0 0 0-1.414 0l-7 7a1 1 0 0 0 1.414 1.414L4 10.414V17a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6.586l.293.293a1 1 0 0 0 1.414-1.414l-7-7Z" />
                        </svg>
                        <span>Home</span>
                        @if (request()->routeIs($prefix . '.home'))
                            <span class="sr-only">active</span>
                        @endif
                    </a>

                    <a href="{{ route('teacher.my-sections') }}" wire:navigate
                        class="flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium {{ request()->routeIs($prefix . '.my-sections') ? 'bg-primary/80 text-on-primary dark:bg-primary-dark/10 dark:text-on-surface-dark-strong' : 'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong' }} underline-offset-2 focus-visible:underline focus:outline-hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-5 shrink-0" aria-hidden="true">
                            <path
                                d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                        </svg>
                        <span>My Sections</span>
                    </a>

                    <a href="#"
                        class="flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-5 shrink-0" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Settings</span>
                    </a>
                @endif
            </div>
        </nav>

        <!-- top navbar & main content  -->
        <div class="h-svh w-full overflow-y-auto bg-surface dark:bg-surface-dark">
            <!-- top navbar  -->
            <nav class="sticky top-0 z-10 flex items-center justify-between border-b border-outline bg-primary px-4 py-2 dark:border-outline-dark dark:bg-surface-dark-alt"
                aria-label="top navibation bar">

                <!-- sidebar toggle button for small screens  -->
                <button type="button" class="md:hidden inline-block  dark:text-on-surface-dark"
                    x-on:click="sidebarIsOpen = true">
                    <img src="{{ asset('images/logo.png') }}" alt="menu icon" class="h-8 w-8" />
                    <span class="sr-only">sidebar toggle</span>
                </button>

                <!-- breadcrumbs  -->
                <nav class="hidden md:inline-block text-sm font-medium text-on-primary dark:text-on-surface-dark"
                    aria-label="breadcrumb">
                    {{ $breadcrumbs ?? '' }}
                </nav>


                <!-- Profile Menu  -->
                <div x-data="{ userDropdownIsOpen: false }" class="relative" x-on:keydown.esc.window="userDropdownIsOpen = false">
                    <button type="button"
                        class="flex w-full items-center rounded-radius gap-2 p-2 text-left text-on-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark"
                        x-bind:class="userDropdownIsOpen ? 'bg-primary/10 dark:bg-primary-dark/10' : ''"
                        aria-haspopup="true" x-on:click="userDropdownIsOpen = ! userDropdownIsOpen"
                        x-bind:aria-expanded="userDropdownIsOpen">
                        <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Sarah"
                            class="size-8 object-cover rounded-radius" alt="avatar" />
                        <div class="hidden md:flex flex-col">
                            <span
                                class="text-sm font-bold text-on-primary dark:text-on-surface-dark-strong">{{ Auth::user()->name }}</span>
                            <span class="text-xs" aria-hidden="true">{{ Auth::user()->email }}</span>
                            <span class="sr-only">profile settings</span>
                        </div>
                    </button>

                    <!-- menu -->
                    <div x-cloak x-show="userDropdownIsOpen"
                        class="absolute top-14 right-0 z-20 h-fit w-48 border divide-y divide-outline border-outline bg-surface dark:divide-outline-dark dark:border-outline-dark dark:bg-surface-dark rounded-radius"
                        role="menu" x-on:click.outside="userDropdownIsOpen = false"
                        x-on:keydown.down.prevent="$focus.wrap().next()"
                        x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition="" x-trap="userDropdownIsOpen">

                        <div class="flex flex-col py-1.5">
                            <a href="#"
                                class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong"
                                role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5 shrink-0" aria-hidden="true">
                                    <path
                                        d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                </svg>
                                <span>Profile</span>
                            </a>
                        </div>

                        <div class="flex flex-col py-1.5">
                            <a href="#"
                                class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong"
                                role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5 shrink-0" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Settings</span>
                            </a>
                            <a href="#"
                                class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong"
                                role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5 shrink-0" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M2.5 4A1.5 1.5 0 0 0 1 5.5V6h18v-.5A1.5 1.5 0 0 0 17.5 4h-15ZM19 8.5H1v6A1.5 1.5 0 0 0 2.5 16h15a1.5 1.5 0 0 0 1.5-1.5v-6ZM3 13.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Zm4.75-.75a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Payments</span>
                            </a>
                        </div>

                        <div class="flex flex-col py-1.5">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" aria-label="Sign out"
                                    class="w-full text-left flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong"
                                    role="menuitem">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5 shrink-0" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                            clip-rule="evenodd" />
                                        <path fill-rule="evenodd"
                                            d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- main content  -->
            <div id="main-content" class="p-4">
                <div>
                    <!-- Add main content here  -->
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>
