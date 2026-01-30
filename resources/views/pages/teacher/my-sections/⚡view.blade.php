<?php

use App\Models\Section;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Section $section;

    public function mount(): void {}

    #[Computed]
    public function studentsCount(): int
    {
        return $this->section->students()->count();
    }

    #[Computed]
    public function groupsCount(): int
    {
        return $this->section->researchGroups()->count();
    }
};
?>

<x-slot name="title">
    {{ $section->name }}
</x-slot>

<x-slot name="breadcrumbs">
    <ol class="flex flex-wrap items-center gap-1">
        <li class="flex items-center gap-1">
            <a href="{{ route('teacher.my-sections') }}" wire:navigate
                class="hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong">My Sections</a>
            <x-heroicon-m-chevron-right class="size-4" />
        </li>

        <li class="flex items-center gap-1 font-bold text-on-primary dark:text-on-surface-dark-strong"
            aria-current="page">{{ $section->name }}</li>
    </ol>
</x-slot>

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
            <a href="#"
                class="flex items-center gap-2 px-4 py-3 text-sm font-bold text-primary border-b-2 border-primary dark:border-primary-dark dark:text-primary-dark transition-colors"
                aria-current="page">
                <x-heroicon-m-newspaper class="size-5" />
                News
            </a>
            <a href="#"
                class="flex items-center gap-2 px-4 py-3 text-sm font-medium text-on-surface dark:text-on-surface-dark hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong dark:hover:border-b-outline-dark-strong transition-colors">
                <x-heroicon-m-user-group class="size-5" />
                Groups
                <span
                    class="text-xs font-medium px-1.5 py-0.5 rounded-full border border-outline dark:border-outline-dark bg-surface-alt dark:bg-surface-dark-alt">
                    {{ $this->groupsCount }}
                </span>
            </a>
            <a href="#"
                class="flex items-center gap-2 px-4 py-3 text-sm font-medium text-on-surface dark:text-on-surface-dark hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong dark:hover:border-b-outline-dark-strong transition-colors">
                <x-heroicon-m-academic-cap class="size-5" />
                Students
                <span
                    class="text-xs font-medium px-1.5 py-0.5 rounded-full border border-outline dark:border-outline-dark bg-surface-alt dark:bg-surface-dark-alt">
                    {{ $this->studentsCount }}
                </span>
            </a>
        </nav>

        {{-- Tab Content --}}
        <div class="py-6">
            <div class="text-on-surface min-h-[1000px] dark:text-on-surface-dark">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- Stats Sidebar (visible only on lg+) --}}
    <aside class="hidden lg:block w-64 fixed right-6 top-24">
        <div class="sticky top-6 space-y-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">Students</span>
                <span class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $this->studentsCount }}
                </span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">Groups</span>
                <span class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $this->groupsCount }}
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
