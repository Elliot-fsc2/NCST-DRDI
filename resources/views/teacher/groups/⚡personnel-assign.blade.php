<?php

use App\Enums\PersonnelAssignmentRole;
use App\Models\ResearchGroup;
use App\Models\Teacher;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

new class extends Component implements HasActions, HasSchemas {
  use InteractsWithActions;
  use InteractsWithSchemas;

  public ResearchGroup $group;

  public function addPersonnelAction(): Action
  {
    return Action::make('addPersonnel')
      ->modalWidth('lg')
      ->modalHeading('Add Personnel')
      ->schema([
        Select::make('teacher_id')
          ->label('Teacher')
          ->options(function () {
            // Get teachers not already assigned to this group
            $assignedTeacherIds = $this->group->personnelAssignments()->pluck('teacher_id');

            return Teacher::whereNotIn('id', $assignedTeacherIds)
              ->get()
              ->mapWithKeys(fn($teacher) => [
                $teacher->id => "{$teacher->name} ({$teacher->role->getLabel()})",
              ]);
          })
          ->searchable()
          ->required(),
        Select::make('role')
          ->label('Assignment Role')
          ->options(PersonnelAssignmentRole::class)
          ->required(),
      ])
      ->action(function (array $data) {
        $this->group->personnelAssignments()->create([
          'teacher_id' => $data['teacher_id'],
          'role' => $data['role'],
        ]);

        $teacher = Teacher::find($data['teacher_id']);

        Notification::make()
          ->title('Personnel added successfully')
          ->success()
          ->body("{$teacher->name} has been assigned as {$data['role']->getLabel()}.")
          ->send();

        $this->group->loadCount('personnelAssignments');
        $this->group->load('personnelAssignments.teacher.department');
      });
  }

  public function removePersonnelAction(): Action
  {
    return Action::make('removePersonnel')
      ->color('danger')
      ->requiresConfirmation()
      ->modalDescription('Are you sure you want to remove this personnel assignment?')
      ->action(function (array $arguments) {
        $assignmentId = $arguments['assignment'];
        $assignment = $this->group->personnelAssignments()->findOrFail($assignmentId);
        $teacherName = $assignment->teacher->name;

        $assignment->delete();

        Notification::make()
          ->title('Personnel removed')
          ->success()
          ->body("{$teacherName} has been removed from this group.")
          ->send();

        $this->group->loadCount('personnelAssignments');
        $this->group->load('personnelAssignments.teacher.department');
      });
  }
};
?>

<div>
  <div class="flex items-center justify-between mb-4 sm:mb-6">
    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Personnel Assignment</h2>
    <div class="flex items-center gap-2 sm:gap-3">
      <span
        class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 bg-purple-50 text-purple-700 rounded-full text-[10px] sm:text-xs font-semibold whitespace-nowrap">
        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        {{ $group->personnel_assignments_count ?? $group->personnelAssignments->count() }}
        {{ Str::plural('Personnel', $group->personnel_assignments_count ?? $group->personnelAssignments->count()) }}
      </span>
      <x-filament::button wire:click="mountAction('addPersonnelAction')" color="primary" size="sm">
        <span class="hidden sm:inline">Add Personnel</span>
        <span class="sm:hidden">Add</span>
      </x-filament::button>
    </div>
  </div>

  <div class="space-y-2 sm:space-y-3">
    @forelse($group->personnelAssignments as $assignment)
      <div wire:key="personnel-{{ $assignment->id }}"
        class="flex items-center gap-2 sm:gap-3 lg:gap-4 p-3 sm:p-4 bg-gray-50 rounded-xl sm:rounded-2xl hover:bg-gray-100 transition-colors">
        <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white shadow-sm"
          src="https://ui-avatars.com/api/?name={{ urlencode($assignment->teacher->name) }}&background=random&color=fff&size=128"
          alt="{{ $assignment->teacher->name }}">
        <div class="flex-1 min-w-0">
          <h3 class="font-semibold text-sm sm:text-base text-gray-900 truncate">{{ $assignment->teacher->name }}</h3>
          <p class="text-xs sm:text-sm text-gray-500">
            {{ $assignment->teacher->department->name ?? 'N/A' }} • {{ $assignment->teacher->role->getLabel() }}
          </p>
        </div>
        <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
          <span
            class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 sm:px-2.5 py-0.5 sm:py-1 bg-blue-100 text-blue-800 rounded-lg text-[10px] sm:text-xs font-semibold">
            {{ $assignment->role->getLabel() }}
          </span>
          <x-filament::icon-button icon="heroicon-o-trash" color="danger"
            wire:click="mountAction('removePersonnelAction', {assignment: {{ $assignment->id }}})"
            tooltip="Remove personnel" size="sm" />
        </div>
      </div>
    @empty
      <div class="flex flex-col items-center justify-center py-8 sm:py-12">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-100 flex items-center justify-center mb-3 sm:mb-4">
          <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">No personnel assigned</h3>
        <p class="text-xs sm:text-sm text-gray-500 text-center px-4">Assign teachers to help with this research group</p>
      </div>
    @endforelse
  </div>
  <x-filament-actions::modals />
</div>