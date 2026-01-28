<?php

use Livewire\Component;

new class extends Component {
    public $news = [];
    public $sections = [];

    public function mount(): void
    {
        // Mock news data - will be replaced with actual database queries
        $this->news = [
            [
                'id' => 1,
                'title' => 'Welcome to the New Academic Year',
                'content' => 'We are excited to welcome all faculty members to the new academic year. Please check your course assignments and prepare for the upcoming semester.',
                'published_at' => now()->subDays(2),
            ],
            [
                'id' => 2,
                'title' => 'Research Symposium Announcement',
                'content' => 'The annual research symposium will be held next month. All faculty members are encouraged to submit their research proposals by the end of this week.',
                'published_at' => now()->subDays(5),
            ],
            [
                'id' => 3,
                'title' => 'Department Meeting Schedule',
                'content' => 'Monthly department meetings will resume next week. Please mark your calendars for the first Tuesday of each month at 2:00 PM.',
                'published_at' => now()->subWeek(),
            ],
            [
                'id' => 3,
                'title' => 'Department Meeting Schedule',
                'content' => 'Monthly department meetings will resume next week. Please mark your calendars for the first Tuesday of each month at 2:00 PM.',
                'published_at' => now()->subWeek(),
            ],
            [
                'id' => 3,
                'title' => 'Department Meeting Schedule',
                'content' => 'Monthly department meetings will resume next week. Please mark your calendars for the first Tuesday of each month at 2:00 PM.',
                'published_at' => now()->subWeek(),
            ],
            [
                'id' => 3,
                'title' => 'Department Meeting Schedule',
                'content' => 'Monthly department meetings will resume next week. Please mark your calendars for the first Tuesday of each month at 2:00 PM.',
                'published_at' => now()->subWeek(),
            ],
            [
                'id' => 3,
                'title' => 'Department Meeting Schedule',
                'content' => 'Monthly department meetings will resume next week. Please mark your calendars for the first Tuesday of each month at 2:00 PM.',
                'published_at' => now()->subWeek(),
            ],
            [
                'id' => 3,
                'title' => 'Department Meeting Schedule',
                'content' => 'Monthly department meetings will resume next week. Please mark your calendars for the first Tuesday of each month at 2:00 PM.',
                'published_at' => now()->subWeek(),
            ],
        ];

        // Mock sections data - will be replaced with actual teacher sections
        $this->sections = [['id' => 1, 'code' => 'CS-101-A', 'name' => 'Introduction to Programming', 'students_count' => 35], ['id' => 2, 'code' => 'CS-201-B', 'name' => 'Data Structures', 'students_count' => 28], ['id' => 3, 'code' => 'CS-301-A', 'name' => 'Advanced Algorithms', 'students_count' => 22]];
    }
};
?>

<x-slot name="title">
    Teacher Home
</x-slot>

<x-slot name="breadcrumbs">
    <ol class="flex flex-wrap items-center gap-1">
        <li class="flex items-center gap-1 font-bold text-on-primary dark:text-on-surface-dark-strong"
            aria-current="page">Home</li>
    </ol>
</x-slot>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content - News -->
    <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">Latest News</h1>
        </div>

        <div class="space-y-4">
            @forelse($news as $item)
                <article
                    class="bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark rounded-radius p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <h2 class="text-xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                            {{ $item['title'] }}
                        </h2>
                        <time class="text-xs text-on-surface/60 dark:text-on-surface-dark/60 whitespace-nowrap ml-4">
                            {{ $item['published_at']->diffForHumans() }}
                        </time>
                    </div>
                    <p class="text-on-surface dark:text-on-surface-dark leading-relaxed">
                        {{ $item['content'] }}
                    </p>
                </article>
            @empty
                <div
                    class="bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark rounded-radius p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-12 w-12 mx-auto mb-4 text-on-surface/30 dark:text-on-surface-dark/30" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-on-surface/60 dark:text-on-surface-dark/60">No news available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Aside - Sections Card -->
    <aside class="lg:col-span-1 sticky top-20 self-start">
        <div
            class="bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark rounded-radius p-4">
            <h2 class="text-lg font-semibold text-on-surface-strong dark:text-on-surface-dark-strong mb-4">My Sections
            </h2>

            <div class="space-y-2">
                @forelse($sections as $section)
                    <a href="#"
                        class="block p-3 rounded-radius hover:bg-primary/5 dark:hover:bg-primary-dark/5 transition-colors border border-transparent hover:border-primary/20 dark:hover:border-primary-dark/20">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-medium text-on-surface-strong dark:text-on-surface-dark-strong truncate">
                                    {{ $section['code'] }}
                                </p>
                                <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 line-clamp-1 mt-0.5">
                                    {{ $section['name'] }}
                                </p>
                            </div>
                            <span
                                class="ml-2 inline-flex items-center justify-center min-w-[2rem] h-5 px-1.5 text-xs font-medium bg-primary/10 dark:bg-primary-dark/10 text-primary dark:text-primary-dark rounded-radius">
                                {{ $section['students_count'] }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 mx-auto mb-3 text-on-surface/20 dark:text-on-surface-dark/20"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-xs text-on-surface/50 dark:text-on-surface-dark/50">No sections assigned</p>
                    </div>
                @endforelse
            </div>

            @if (count($sections) > 0)
                <div class="mt-4 pt-4 border-t border-outline dark:border-outline-dark">
                    <a href="#"
                        class="text-xs text-primary dark:text-primary-dark hover:underline font-medium flex items-center justify-center gap-1">
                        View all sections
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </aside>
</div>
