<?php

use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use WithPagination;

    public function sections()
    {
        $paginator = Section::where('teacher_id', auth()->user()->profile->id)
            ->withCourse()
            ->withStudents()
            ->withSemester()
            ->withTeacher()
            ->paginate(10);

        $summary = $paginator->getCollection()->sectionSummary();

        return new LengthAwarePaginator($summary, $paginator->total(), $paginator->perPage(), $paginator->currentPage(), ['path' => $paginator->path()]);
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

<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($this->sections() as $section)
            <x-section-card :section="$section" />
        @endforeach
    </div>

    <div class="mt-6">
        {{ $this->sections()->links() }}
    </div>
</div>

<script>
    $wire.sections().then(paginator => {
        let sections = paginator.data || paginator;

        // Log all students from all sections
        if (Array.isArray(sections)) {
            sections.forEach(section => {
                console.log(`Section: ${section.name} (${section.student_count} students)`);
                section.students.forEach(student => {
                    console.log(`  - ${student.name} (ID: ${student.id})`);
                });
            });
        } else {
            console.log('Sections data:', paginator);
        }
    });
</script>
