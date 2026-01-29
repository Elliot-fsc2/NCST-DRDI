<?php

use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use WithPagination;

    public function sections(): LengthAwarePaginator
    {
        return Section::where('teacher_id', auth()->user()->profile->id)
            ->withCourse()
            ->withStudents()
            ->withSemester()
            ->withTeacher()
            ->paginate(10);
    }

    public function mount()
    {
        dd($this->sections()->sectionSummary());
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
    hi
</div>

<script>
    let count = $wire.count;
    console.log(count);
</script>
