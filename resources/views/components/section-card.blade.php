@props(['section'])

<article
    class="group h-full w-full flex flex-col overflow-hidden rounded-lg border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
    <div class="h-40 shrink-0 overflow-hidden">
        <img src="{{ asset('images/class.png') }}"
            class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105"
            alt="a penguin robot talking with a human" />
    </div>
    <div class="flex flex-1 flex-col gap-2 p-4">
        <span
            class="text-xs font-medium uppercase tracking-wide text-on-surface/70 dark:text-on-surface-dark/70">{{ $section->course?->name ?? 'Course' }}</span>
        <h3 class="text-lg font-bold text-on-surface-strong dark:text-on-surface-dark-strong"
            aria-describedby="sectionDescription">{{ $section->name ?? 'Section' }}</h3>
        <div id="sectionDescription" class="mt-auto space-y-1 text-sm">
            <p><span class="font-medium">Teacher:</span> {{ $section->teacher?->name ?? 'TBA' }}</p>
            <p><span class="font-medium">Semester:</span> {{ $section->semester?->name ?? 'TBA' }}</p>
            <p><span class="font-medium">Students:</span> {{ $section->students_count ?? 0 }}</p>
        </div>
    </div>
</article>
