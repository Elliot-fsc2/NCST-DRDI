<?php

use App\Models\Section;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

new #[Title('My Sections')]
  class extends Component implements HasActions, HasSchemas {
  use InteractsWithActions;
  use InteractsWithSchemas;

  #[Computed]
  #[On('section-created')]
  public function sections()
  {
    return Section::where('teacher_id', auth()->user()->profile->id)
      ->with('course')
      ->withCount('students')
      ->get();
  }

  public function deleteAction(): Action
  {
    return Action::make('delete')
      ->color('danger')
      ->action(function (array $arguments) {
        $section = Section::find($arguments['section']);
        $section->delete();
      })
      ->successNotificationTitle('Section deleted successfully.')
      ->requiresConfirmation();
  }
};
?>

@assets
<link href="{{ Vite::asset('resources/css/filament/app.css') }}" rel="stylesheet">
@endassets

<div x-data="{ openModal: false }" x-on:section-created.window="openModal = false"
  class="max-w-7xl mx-auto px-4 py-6 font-interface">

  <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 tracking-tight leading-none">My Sections</h1>
      <p class="text-sm text-gray-500 mt-2 font-medium">View and manage your current semester load.</p>
    </div>

    <button x-on:click="openModal = true"
      class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all flex items-center shadow-md group">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
        class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
      Create Section
    </button>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
      <div class="bg-blue-50 text-blue-600 p-3 rounded-xl font-semibold">
        {{ $this->sections->count() }}
      </div>
      <div>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Load</p>
        <p class="text-sm font-semibold text-gray-800">Assigned Sections</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($this->sections as $section)
      <div
        class="group bg-white rounded-4xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="p-7">
          <div class="flex justify-between items-start mb-6">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A57.43 57.43 0 0 1 12 15.75a57.433 57.433 0 0 1 5.25-4.425V15" />
              </svg>
            </div>

            <div class="relative" x-data="{ open: false }">
              <button @click="open = !open" @click.away="open = false" class="hover:text-gray-500 p-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                  stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                </svg>
              </button>
              <div x-show="open" x-cloak x-transition.origin.top.right
                class="absolute right-0 mt-2 w-40 bg-white border border-gray-100 rounded-xl shadow-xl z-10 py-2">
                <a href="#" class="block px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50">Edit Section</a>
                <button wire:click="mountAction('deleteAction', {section: {{ $section->id }}})"
                  class="block w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Delete</button>
              </div>
            </div>
          </div>

          <h3 class="text-lg font-bold text-gray-900 leading-tight mb-1">{{ $section->name }}</h3>
          <p class="text-sm text-blue-600 font-semibold mb-6 tracking-tight">{{ $section->course->name }}</p>

          <div class="flex items-center space-x-4 text-[11px] font-bold text-gray-400 mb-8 uppercase tracking-widest">
            <span class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4 mr-2 text-gray-300">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
              </svg>
              {{ $section->students_count }} Students
            </span>
          </div>

          <div class="flex items-center justify-between pt-6 border-t border-gray-50">
            <a href="{{ route('teacher.my-sections.view', ['section' => $section]) }}" wire:navigate
              class="inline-flex items-center px-5 py-2 bg-gray-900 text-white text-[11px] font-bold rounded-xl hover:bg-blue-600 transition-all shadow-md uppercase tracking-widest">
              Open Dashboard
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full py-16 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
        <p class="text-gray-400 font-medium">No sections assigned yet.</p>
      </div>
    @endforelse
  </div>

  <div x-cloak x-show="openModal" class="fixed inset-0 z-100 flex items-center justify-center p-4">
    <div x-show="openModal" x-transition.opacity @click="openModal = false"
      class="fixed inset-0 bg-gray-900/40 backdrop-blur-md"></div>

    <div x-show="openModal" x-transition.scale.95
      class="relative bg-white w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl overflow-hidden">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">New Section</h2>
        <p class="text-sm text-gray-500 font-medium mt-1">Add a new class group.</p>
      </div>

      <livewire:section.create-section />
    </div>
  </div>
  <x-filament-actions::modals />

</div>
