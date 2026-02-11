<?php

use App\Models\Section;
use Livewire\Component;
use Livewire\Attributes\Title;

new
  #[Title('Add Student')]
  class extends Component {
  public Section $section;
};
?>
<div class="max-w-7xl mx-auto px-4 py-1 font-interface">
  <div class="mb-8">
    <nav class="flex text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
      <a href="{{ route('teacher.my-sections') }}" wire:navigate class="hover:text-blue-600 transition-colors">
        My Sections
      </a>
      <span class="mx-2">/</span>
      <a href="{{ route('teacher.my-sections.view', $section) }}" wire:navigate
        class="hover:text-blue-600 transition-colors">
        {{ $section->name }}
      </a>
      <span class="mx-2">/</span>
      <span class="text-gray-900">Add Student</span>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Add Student to {{ $section->name }}</h1>
  </div>

  <div class="max-w-2xl rounded-4xl border border-gray-100 py-5 px-10 shadow-sm">
    <livewire:group.add-students :section="$section" />
  </div>


</div>
