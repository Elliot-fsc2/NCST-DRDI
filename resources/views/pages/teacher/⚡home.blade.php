<?php

use Livewire\Component;

new class extends Component {
    //
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

<div>
    <div x-data="{ count: 0 }">
        <button x-on:click="count++"
            class="rounded-radius bg-primary/80 px-4 py-2 text-sm font-medium text-on-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-primary-dark/10 dark:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark">
            Click me
        </button>
        <span class="ml-2 text-sm font-medium text-on-surface dark:text-on-surface-dark">
            You've clicked the button <span x-text="count"></span> times.
        </span>
    </div>
</div>
