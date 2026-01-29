<?php

use App\Models\Course;
use App\Models\Section;
use App\Models\Teacher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    #[Url]
    public ?int $course_id = null;

    #[Computed]
    public function teacher(): ?Teacher
    {
        return auth()->user()?->profile;
    }

    #[Computed]
    public function courses()
    {
        return Course::where('department_id', $this->teacher?->department_id)->orderBy('name')->get();
    }

    #[Computed]
    public function sections()
    {
        return Section::where('teacher_id', auth()->id())
            ->when($this->course_id, fn($query) => $query->where('course_id', $this->course_id))
            ->with(['course', 'teacher', 'semester'])
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
    }

    public function updatedCourseId(): void
    {
        $this->resetPage();
    }
};
?>

<x-slot name="title">
    My Sections
</x-slot>

<x-slot name="breadcrumbs">
    <ol class="flex flex-wrap items-center gap-1">
        <li class="flex items-center gap-1 font-bold text-on-primary dark:text-on-surface-dark-strong"
            aria-current="page">My Sections</li>
    </ol>
</x-slot>

<div class="space-y-8">
    {{-- Header & Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                My Sections
            </h1>
            <p class="mt-1 text-sm text-on-surface/70 dark:text-on-surface-dark/70">
                Manage and view all your assigned course sections
            </p>
        </div>

        {{-- Filter --}}
        <div class="relative">
            <label for="course_filter" class="sr-only">Filter by Course</label>
            <select id="course_filter" wire:model.live="course_id"
                class="appearance-none w-full sm:w-64 pl-4 pr-10 py-2.5 border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                <option value="">All Courses</option>
                @foreach ($this->courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-on-surface/50 dark:text-on-surface-dark/50">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div
            class="p-4 bg-surface-alt dark:bg-surface-dark-alt rounded-xl border border-outline/50 dark:border-outline-dark/50">
            <p class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">Total Sections</p>
            <p class="mt-1 text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                {{ $this->sections->total() }}
            </p>
        </div>
        <div
            class="p-4 bg-surface-alt dark:bg-surface-dark-alt rounded-xl border border-outline/50 dark:border-outline-dark/50">
            <p class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">Total Students</p>
            <p class="mt-1 text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                {{ $this->sections->sum('students_count') }}
            </p>
        </div>
        <div
            class="p-4 bg-surface-alt dark:bg-surface-dark-alt rounded-xl border border-outline/50 dark:border-outline-dark/50">
            <p class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">Courses</p>
            <p class="mt-1 text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                {{ $this->courses->count() }}
            </p>
        </div>
        <div
            class="p-4 bg-surface-alt dark:bg-surface-dark-alt rounded-xl border border-outline/50 dark:border-outline-dark/50">
            <p class="text-sm text-on-surface/70 dark:text-on-surface-dark/70">This Page</p>
            <p class="mt-1 text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                {{ $this->sections->count() }}
            </p>
        </div>
    </div>

    {{-- Section Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" wire:loading.class="opacity-50">
        @forelse ($this->sections as $section)
            <x-section-card :section="$section" :key="$section->id" />
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <div
                    class="size-16 mb-4 rounded-full bg-surface-alt dark:bg-surface-dark-alt flex items-center justify-center">
                    <svg class="size-8 text-on-surface/40 dark:text-on-surface-dark/40"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-17.5 0a2.25 2.25 0 0 0-2.25 2.25v4.5a2.25 2.25 0 0 0 2.25 2.25h19.5a2.25 2.25 0 0 0 2.25-2.25v-4.5a2.25 2.25 0 0 0-2.25-2.25m-17.5 0V6.75a2.25 2.25 0 0 1 2.25-2.25h15a2.25 2.25 0 0 1 2.25 2.25v6.75" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">No sections
                    found</h3>
                <p class="mt-1 text-sm text-on-surface/60 dark:text-on-surface-dark/60">
                    @if ($this->course_id)
                        Try selecting a different course or clear the filter.
                    @else
                        You don't have any sections assigned yet.
                    @endif
                </p>
                @if ($this->course_id)
                    <button wire:click="$set('course_id', null)"
                        class="mt-4 px-4 py-2 text-sm font-medium text-primary hover:text-primary-dark dark:text-primary-light dark:hover:text-primary transition-colors">
                        Clear filter
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($this->sections->hasPages())
        <div class="pt-4 border-t border-outline/30 dark:border-outline-dark/30">
            {{ $this->sections->links() }}
        </div>
    @endif
</div>

<script></script>
