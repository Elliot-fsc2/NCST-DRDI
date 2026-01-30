@props(['section'])


<div class="flex gap-6">
    {{-- Main Content --}}
    <div class="flex-1 min-w-0 lg:mr-72">
        {{-- Section Header --}}
        <div class="mb-6 lg:hidden">
            <h1 class="text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                {{ $section->name }}
            </h1>
            <p class="mt-1 text-sm text-on-surface/70 dark:text-on-surface-dark/70">
                {{ $section->course?->name ?? 'Course' }} &bull; {{ $section->semester?->name ?? 'Semester' }}
            </p>
        </div>

        {{-- Navigation Tabs --}}
        <nav class="flex gap-1 overflow-x-auto border-b border-outline dark:border-outline-dark"
            aria-label="Section navigation">
            <a href="{{ route('teacher.my-sections.view', ['section' => $section]) }}" wire:navigate
                class="flex items-center gap-2 px-4 py-3 text-sm font-medium {{ request()->routeIs('teacher.my-sections.view') ? 'bg-primary/80 text-on-primary dark:bg-primary-dark/10 dark:text-on-surface-dark-strong' : 'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong' }} underline-offset-2 focus-visible:underline focus:outline-hidden transition-colors">
                <x-heroicon-m-newspaper class="size-5" />
                News
                @if (request()->routeIs('teacher.my-sections.view'))
                    <span class="sr-only">active</span>
                @endif
            </a>
            <a href="{{ route('teacher.my-sections.view.groups', ['section' => $section]) }}" wire:navigate
                class="flex items-center gap-2 px-4 py-3 text-sm font-medium {{ request()->routeIs('teacher.my-sections.view.groups') ? 'bg-primary/80 text-on-primary dark:bg-primary-dark/10 dark:text-on-surface-dark-strong' : 'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong' }} underline-offset-2 focus-visible:underline focus:outline-hidden transition-colors">
                <x-heroicon-m-user-group class="size-5" />
                Groups
                <span
                    class="text-xs font-medium px-1.5 py-0.5 rounded-full border border-outline dark:border-outline-dark bg-surface-alt dark:bg-surface-dark-alt text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $section->researchGroups->count() }}
                </span>
                @if (request()->routeIs('teacher.my-sections.view.groups'))
                    <span class="sr-only">active</span>
                @endif
            </a>
            <a href="{{ route('teacher.my-sections.view.students', ['section' => $section]) }}" wire:navigate
                class="flex items-center gap-2 px-4 py-3 text-sm font-medium {{ request()->routeIs('teacher.my-sections.view.students') ? 'bg-primary/80 text-on-primary dark:bg-primary-dark/10 dark:text-on-surface-dark-strong' : 'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong' }} underline-offset-2 focus-visible:underline focus:outline-hidden transition-colors">
                <x-heroicon-m-academic-cap class="size-5" />
                Students
                <span
                    class="text-xs font-medium px-1.5 py-0.5 rounded-full border border-outline dark:border-outline-dark bg-surface-alt dark:bg-surface-dark-alt text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $section->students->count() }}
                </span>
                @if (request()->routeIs('teacher.my-sections.view.students'))
                    <span class="sr-only">active</span>
                @endif
            </a>
        </nav>

        {{-- Tab Content --}}
        <div class="py-6">
            <div class="text-on-surface dark:text-on-surface-dark">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- Stats Sidebar (visible only on lg+) --}}
    <aside class="hidden lg:block w-64 fixed right-6 top-30">
        <div class="sticky top-6 space-y-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">Students</span>
                <span class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $section->students->count() }}
                </span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">Groups</span>
                <span class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $section->researchGroups->count() }}
                </span>
            </div>

            <div class="h-px bg-outline/50 dark:bg-outline-dark/50"></div>

            <div class="space-y-1">
                <span
                    class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 uppercase tracking-wide">Course</span>
                <p class="text-sm text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $section->course?->name ?? 'N/A' }}
                </p>
            </div>

            <div class="space-y-1">
                <span
                    class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 uppercase tracking-wide">Semester</span>
                <p class="text-sm text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $section->semester?->name ?? 'N/A' }}
                </p>
            </div>
        </div>
    </aside>
</div>
