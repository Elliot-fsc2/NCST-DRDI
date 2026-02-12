<?php

use App\Models\ResearchGroup;
use App\Models\Section;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Group Details')]
  class extends Component implements HasActions, HasSchemas {
  use InteractsWithSchemas;
  use InteractsWithActions;

  public ResearchGroup $group;
  public Section $section;

  public function title(): string
  {
    return $this->group->name;
  }

  public function mount(): void
  {
    abort_if($this->group->section_id !== $this->section->id, 404);
    abort_if($this->section->teacher_id !== auth()->user()->profile?->id, 404);

    $this->group->loadCount(['members', 'personnelAssignments']);
    $this->group->load(['members', 'course', 'personnelAssignments.teacher.department']);
  }

  #[Computed]
  public function activeTab(): string
  {
    return request()->query('tab', 'members');
  }

  public function editAction(): Action
  {
    return Action::make('editGroup')
      ->modalWidth('lg')
      ->fillForm([
        'name' => $this->group->name,
      ])
      ->action(function (array $data) {
        $this->group->update($data);
      })
      ->successNotificationTitle('Group updated successfully.')
      ->schema([
        TextInput::make('name')
          ->label('Group Name')
          ->required(),
      ]);
  }

  public function deleteAction(): Action
  {
    return Action::make('deleteGroup')
      ->color('danger')
      ->requiresConfirmation()
      ->modalDescription('Are you sure you want to delete this group? This action cannot be undone.')
      ->action(function () {
        $this->group->delete();
        $this->redirect(route('teacher.my-sections.view', ['section' => $this->section->id, 'tab' => 'groups']), true);
      })
      ->successNotificationTitle('Group deleted successfully.');
  }
};
?>

@assets
<link href="{{ Vite::asset('resources/css/filament/app.css') }}" rel="stylesheet">
@endassets

<div class="max-w-7xl mx-auto px-3 sm:px-4 py-1 font-interface">

  {{-- Breadcrumbs --}}
  <div class="mb-6 sm:mb-8">
    <nav
      class="flex flex-wrap text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide sm:tracking-widest mb-2 gap-x-1 sm:gap-x-0">
      <a href="{{ route('teacher.my-sections') }}" wire:navigate class="hover:text-blue-600 transition-colors">
        My Sections
      </a>
      <span class="mx-1 sm:mx-2">/</span>
      <a href="{{ route('teacher.my-sections.view', ['section' => $section->id, 'tab' => 'groups']) }}" wire:navigate
        class="hover:text-blue-600 transition-colors truncate max-w-[100px] sm:max-w-none">
        {{ $section->name }}
      </a>
      <span class="mx-1 sm:mx-2">/</span>
      <span class="text-gray-900">{{ $group->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ $group->name }}</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $section->course->name }} • {{ $section->name }}</p>
      </div>
      <div class="flex items-center gap-2 sm:gap-3">
        <x-filament::button tag="a" wire:navigate
          href="{{ route('teacher.my-sections.groups.add-members', ['section' => $section, 'group' => $group]) }}"
          color="info" size="sm">
          <span class="hidden sm:inline">Add Members</span>
          <span class="sm:hidden">Add</span>
        </x-filament::button>
        <x-filament::button wire:click="mountAction('editAction')" size="sm">
          <span class="hidden sm:inline">Edit Group</span>
          <span class="sm:hidden">Edit</span>
        </x-filament::button>
        <x-filament::button wire:click="mountAction('deleteAction')" color="danger" size="sm">
          Delete
        </x-filament::button>
      </div>
    </div>
  </div>

  {{-- Tabs Navigation --}}
  <div class="flex items-center space-x-8 border-b border-gray-100 mb-6 sm:mb-8">
    <a href="?tab=members" wire:navigate
      class="pb-4 text-sm font-bold uppercase tracking-widest transition-all {{ $this->activeTab === 'members' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
      Members
    </a>
    <a href="?tab=personnel" wire:navigate
      class="pb-4 text-sm font-bold uppercase tracking-widest transition-all {{ $this->activeTab === 'personnel' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
      Personnel Assign
    </a>
  </div>

  {{-- Main Content --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">

    @if($this->activeTab === 'members')
      {{-- Members Section --}}
      <div class="lg:col-span-2 space-y-4 sm:space-y-6">
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 p-4 sm:p-6 lg:p-8 shadow-sm">
          <livewire:teacher::groups.members :group="$group" />
        </div>
      </div>
    @endif

    @if($this->activeTab === 'personnel')
      {{-- Personnel Assign Section --}}
      <div class="lg:col-span-2 space-y-4 sm:space-y-6">
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 p-4 sm:p-6 lg:p-8 shadow-sm">
          <livewire:teacher::groups.personnel-assign :group="$group" />
        </div>
      </div>
    @endif

    {{-- Sidebar --}}
    <div class="space-y-4 sm:space-y-6">

      {{-- Group Info Card --}}
      <div
        class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl sm:rounded-3xl border border-blue-100 p-4 sm:p-6 shadow-sm">
        <h3 class="text-xs sm:text-sm font-bold text-gray-900 uppercase tracking-wide sm:tracking-widest mb-3 sm:mb-4">
          Group Information</h3>
        <div class="space-y-3 sm:space-y-4">
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Group Name</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $group->name }}</p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Section</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $section->name }}</p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Course</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $section->course->name }}</p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Semester</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">
              {{ $section->semester->year . ' ' . $section->semester->phase }}
            </p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Created</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $group->created_at->format('M d, Y') }}</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <x-filament-actions::modals />
</div>