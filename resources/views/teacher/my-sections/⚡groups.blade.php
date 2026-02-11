<?php

use App\Models\Section;
use Livewire\Component;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use App\Services\GroupService;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

new class extends Component implements HasActions, HasSchemas {
  use InteractsWithActions;
  use InteractsWithSchemas;

  public Section $section;
  private GroupService $group;

  public function boot(GroupService $group)
  {
    $this->group = $group;
  }

  public function mount()
  {
    $this->refreshGroups();
  }

  #[On('group-created')]
  public function refreshGroups(): void
  {
    $this->section->load([
      'researchgroups' => function ($query) {
        $query->withCount('members')->with('members');
      },
    ]);
  }

  public function deleteAction(): Action
  {
    return Action::make('delete')
      ->action(function (array $arguments) {
        $groupId = $arguments['groupId'];
        $this->group->delete($groupId);
        $this->refreshGroups();
      })
      ->color('danger')
      ->requiresConfirmation()
      ->modalDescription('Are you sure you want to delete this group? This action cannot be undone.')
      ->successNotificationTitle('Group deleted');
  }

  public function createAction(): Action
  {
    return Action::make('create')
      ->modalWidth('lg')
      ->action(
        function (array $data) {
          $data['section_id'] = $this->section->id;
          $data['course_id'] = $this->section->course_id;
          $this->group->create($data);
          $this->refreshGroups();
        }
      )
      ->successNotificationTitle('Group created')
      ->schema([
        TextInput::make('name')->label('Group Name')->required(),
      ]);

  }
};
?>
<div>

  <div class="mb-6 flex justify-end">
    <x-filament::button wire:click="mountAction('createAction')"
      class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
      Create New Group
    </x-filament::button>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($section->researchgroups as $group)
      <a href="{{ route('teacher.my-sections.groups.view', ['section' => $section, 'group' => $group]) }}" wire:navigate>
        <div
          class="group relative bg-gradient-to-br from-white to-gray-50 rounded-3xl border border-gray-200 overflow-hidden hover:shadow-xl hover:border-blue-200 transition-all duration-300 hover:-translate-y-1">
          {{-- Decorative gradient bar --}}
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

          <div class="p-6">
            {{-- Header --}}
            <div class="flex justify-between items-start mb-5">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">

                  <div>
                    <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $group->name }}</h4>
                    <p class="text-xs text-gray-500 font-medium">Research Group</p>
                  </div>
                </div>
              </div>
            </div>

            {{-- Members count badge --}}
            <div class="flex items-center gap-2 mb-4">
              <div
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>{{ $group->members_count }} {{ Str::plural('Member', $group->members_count) }}</span>
              </div>
            </div>

            {{-- Member avatars --}}
            <div class="pt-4 border-t border-gray-100">
              <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Team Members</p>
              @if($group->members->count() > 0)
                <div class="flex items-center gap-2">
                  <div class="flex -space-x-3">
                    @foreach($group->members->take(4) as $student)
                      <div class="relative group/avatar">
                        <img
                          class="w-10 h-10 rounded-full border-3 border-white ring-2 ring-gray-100 transition-transform hover:scale-110 hover:z-10"
                          src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&color=fff&size=128"
                          alt="{{ $student->name }}" title="{{ $student->name }}">
                      </div>
                    @endforeach
                    @if($group->members->count() > 4)
                      <div
                        class="w-10 h-10 rounded-full border-3 border-white ring-2 ring-gray-100 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <span class="text-xs font-bold text-gray-600">+{{ $group->members->count() - 4 }}</span>
                      </div>
                    @endif
                  </div>
                  @if($group->members->count() > 4)
                    <span class="text-xs text-gray-400 ml-1">and {{ $group->members->count() - 4 }} more</span>
                  @endif
                </div>
              @else
                <p class="text-xs text-gray-400 italic">No members yet</p>
              @endif
            </div>
          </div>

          {{-- Hover effect overlay --}}
          <div
            class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-purple-500/0 group-hover:from-blue-500/5 group-hover:to-purple-500/5 pointer-events-none transition-all duration-300">
          </div>
        </div>
      </a>
    @empty
      <div class="col-span-full flex flex-col items-center justify-center py-16 px-6">
        <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
          <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">No groups yet</h3>
        <p class="text-sm text-gray-500">Create your first research group to get started</p>
      </div>
    @endforelse
  </div>
  <x-filament-actions::modals />
</div>