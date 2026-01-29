@props([
    'section' => null,
    'name' => null,
    'student_count' => null,
])

<article
    class="group h-80 w-full sm:max-w-sm md:max-w-md lg:max-w-lg flex flex-col overflow-hidden border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
    <div class="h-36 md:h-44 lg:h-56 overflow-hidden">
        <img src="https://penguinui.s3.amazonaws.com/component-assets/card-img-1.webp"
            class="object-cover transition duration-700 ease-out group-hover:scale-105"
            alt="a penguin robot talking with a human" />
    </div>
    <div class="flex-1 flex flex-col gap-4 p-6">
        <span class="text-sm font-medium">{{ $section['course'] ?? ($name ?? 'Course') }}</span>
        <h3 class="text-balance text-xl lg:text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong"
            aria-describedby="sectionDescription">{{ $section['name'] ?? ($name ?? 'Section') }}</h3>
        <p id="sectionDescription" class="text-pretty text-sm">
            <strong>Teacher:</strong> {{ $section['teacher'] ?? 'TBA' }}<br>
            <strong>Semester:</strong> {{ $section['semester']['name'] ?? ($section['semester_name'] ?? 'TBA') }}<br>
            <strong>Students:</strong>
            {{ $student_count ?? ($section['student_count'] ?? ($section['student_ids']->count() ?? '0')) }}
        </p>
    </div>
</article>
