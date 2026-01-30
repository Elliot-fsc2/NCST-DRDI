<?php

use App\Models\Section;
use Livewire\Component;

new class extends Component {
    public Section $section;
};
?>

<div>
    <x-my-sections.view :section="$section">
        hi
    </x-my-sections.view>
</div>
