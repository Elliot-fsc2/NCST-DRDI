<?php

use App\Models\Section;
use Livewire\Component;

new class extends Component {
    public Section $section;
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

<x-my-sections.view :section="$section">
    hi
</x-my-sections.view>
