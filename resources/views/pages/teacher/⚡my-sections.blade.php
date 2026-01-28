<?php

use Livewire\Component;

new class extends Component {
    public $count = 12;
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
