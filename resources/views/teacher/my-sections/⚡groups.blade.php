<?php

use App\Models\Section;
use App\Models\ResearchGroup;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {

  public Section $section;

  public function mount()
  {
    $this->refreshGroups();
  }
  #[On('group-created')]
  public function refreshGroups(): void
  {
    $this->section->load([
      'researchgroups' => function ($query) {
        $query->withCount('students')->with('students');
      },
    ]);
  }

  public function deleteGroup(int $groupId): void
  {
    $group = ResearchGroup::findOrFail($groupId);
    $group->delete();
    $group->students()->detach();
    $this->refreshGroups();
  }

  public function viewGroup(int $groupId): void
  {
    // Implement view logic
  }

  public function editGroup(int $groupId): void
  {
    // Implement edit logic
  }
};
?>
<div>

  <div class="mb-6 flex justify-end">
    <x-filament::modal width="xl" id="create-group">
      <x-slot name="trigger">
        <x-filament::button
          class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
          Create New Group
        </x-filament::button>
      </x-slot>
      <x-slot name="heading">
        Create Research Group
      </x-slot>
      <livewire:group.create-group :section="$section" />
    </x-filament::modal>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($section->researchgroups as $group)
      <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h4 class="font-bold text-gray-900">Group {{ $loop->iteration }}</h4>
            <p class="text-xs text-gray-500 mt-1">{{ $group->students_count }} members</p>
          </div>
          <x-filament::dropdown placement="bottom-end">
            <x-slot name="trigger">
              <x-filament::icon-button icon="heroicon-o-ellipsis-vertical" color="gray" />
            </x-slot>

            <x-filament::dropdown.list>
              <x-filament::dropdown.list.item icon="heroicon-o-eye" wire:click="viewGroup({{ $group->id }})">
                View Details
              </x-filament::dropdown.list.item>

              <x-filament::dropdown.list.item icon="heroicon-o-pencil" wire:click="editGroup({{ $group->id }})">
                Edit Group
              </x-filament::dropdown.list.item>

              <x-filament::dropdown.list.item icon="heroicon-o-trash" color="danger"
                wire:confirm="Are you sure you want to delete this group?" wire:click="deleteGroup({{ $group->id }})">
                Delete Group
              </x-filament::dropdown.list.item>
            </x-filament::dropdown.list>
          </x-filament::dropdown>
        </div>
        <div class="flex items-center justify-between">
          <div class="flex -space-x-2">
            @foreach($group->students->take(3) as $student)
              <img class="w-8 h-8 rounded-full border-2 border-white"
                src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}" alt="{{ $student->name }}"
                title="{{ $student->name }}">
            @endforeach
            @if($group->students->count() > 3)
              <div
                class="w-8 h-8 rounded-full border-2 border-white bg-gray-50 flex items-center justify-center text-[10px] font-bold text-gray-400">
                +{{ $group->students->count() - 3 }}
              </div>
            @endif
          </div>
        </div>
      </div>
    @empty
      <p class="text-gray-400 italic text-center col-span-2 py-8">No groups formed yet.</p>
    @endforelse
  </div>
</div>
