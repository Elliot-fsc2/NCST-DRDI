<?php

use App\Models\Section;
use Livewire\Component;

new class extends Component {
    public Section $section;
    public array $newsItems = [
        [
            'title' => 'Welcome to the Course!',
            'date' => '2024-09-01',
            'content' => 'We are excited to have you in this course. Stay tuned for updates and announcements.',
        ],
        [
            'title' => 'First Assignment Released',
            'date' => '2024-09-05',
            'content' => 'The first assignment is now available on the course portal. Please check it out and submit by the due date.',
        ],
        [
            'title' => 'Guest Lecture Scheduled',
            'date' => '2024-09-10',
            'content' => 'We have a special guest lecture scheduled for next week. Don\'t miss it!',
        ],
        [
            'title' => 'Guest Lecture Scheduled',
            'date' => '2024-09-10',
            'content' => 'We have a special guest lecture scheduled for next week. Don\'t miss it!',
        ],
        [
            'title' => 'Guest Lecture Scheduled',
            'date' => '2024-09-10',
            'content' => 'We have a special guest lecture scheduled for next week. Don\'t miss it!',
        ],
        [
            'title' => 'Guest Lecture Scheduled',
            'date' => '2024-09-10',
            'content' => 'We have a special guest lecture scheduled for next week. Don\'t miss it!',
        ],
    ];
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

<div>
    <x-my-sections.view :section="$section">
        <div>
            @foreach ($newsItems as $item)
                <div
                    class="mb-6 p-4 border border-outline dark:border-outline-dark rounded-lg bg-surface-alt dark:bg-surface-dark-alt">
                    <h3 class="text-lg font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                        {{ $item['title'] }}</h3>
                    <p class="text-sm text-on-surface/70 dark:text-on-surface-dark/70 mb-2">
                        {{ \Carbon\Carbon::parse($item['date'])->format('F j, Y') }}</p>
                    <p class="text-on-surface dark:text-on-surface-dark">{{ $item['content'] }}</p>
                </div>
            @endforeach
        </div>
    </x-my-sections.view>
</div>
