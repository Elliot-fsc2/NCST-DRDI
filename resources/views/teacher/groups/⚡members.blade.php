<?php

use Livewire\Component;
use Filament\Actions\Action;
use App\Models\ResearchGroup;
use Filament\Notifications\Notification;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Actions\Contracts\HasActions;

new class extends Component implements HasActions, HasSchemas {
  use InteractsWithActions;
  use InteractsWithSchemas;

  public ResearchGroup $group;

  public function toggleLeader($memberId): void
  {
    $member = $this->group->members()->findOrFail($memberId);

    if ($member->is_leader) {
      $member->update(['is_leader' => false]);
      // $this->dispatch('notify', title: 'Leader status removed');
      Notification::make()
        ->title('Leader status removed')
        ->success()
        ->body("{$member->name} is no longer the group leader.")
        ->send();
    } else {
      // Remove leader status from all other members
      $this->group->members()->update(['is_leader' => false]);
      // Set this member as leader
      $member->update(['is_leader' => true]);
      Notification::make()
        ->title('Leader status assigned')
        ->success()
        ->body("{$member->name} is now the group leader.")
        ->send();
    }

    $this->group->load('members');
  }

  public function removeMember($memberId): void
  {
    $member = $this->group->members()->findOrFail($memberId);
    $member->delete();

    $this->group->loadCount('members');
    $this->group->load('members');

    Notification::make()
      ->title('Member removed successfully')
      ->success()
      ->body("{$member->name} has been removed from the group.")
      ->send();
  }

  public function removeMemberAction(): Action
  {
    return Action::make('delete')
      ->action(function (array $arguments) {
        $studentId = $arguments['student'];
        $this->group->members()->where('id', $studentId)->delete();
      })
      ->color('danger')
      ->successNotificationTitle('Student removed')
      ->successNotificationMessage('The student has been removed from the group.')
      ->requiresConfirmation();
  }
};
?>
<div>

  <div class="flex items-center justify-between mb-4 sm:mb-6">
    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Group Members</h2>
    <span
      class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 bg-blue-50 text-blue-700 rounded-full text-[10px] sm:text-xs font-semibold whitespace-nowrap">
      <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      {{ $group->members_count }} {{ Str::plural('Member', $group->members_count) }}
    </span>
  </div>

  <div class="space-y-2 sm:space-y-3">
    @forelse($group->members->sortByDesc('is_leader') as $member)
      <div wire:key="member-{{ $member->id }}"
        class="flex items-center gap-2 sm:gap-3 lg:gap-4 p-3 sm:p-4 bg-gray-50 rounded-xl sm:rounded-2xl hover:bg-gray-100 transition-colors">
        <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white shadow-sm"
          src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random&color=fff&size=128"
          alt="{{ $member->name }}">
        <div class="flex-1 min-w-0">
          <h3 class="font-semibold text-sm sm:text-base text-gray-900 truncate">{{ $member->name }}</h3>
          <p class="text-xs sm:text-sm text-gray-500">Member</p>
        </div>
        <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
          @if($member->is_leader)
            <span
              class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 sm:px-2.5 py-0.5 sm:py-1 bg-yellow-100 text-yellow-800 rounded-lg text-[10px] sm:text-xs font-semibold">
              <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <span class="hidden sm:inline">Leader</span>
            </span>
          @endif
          <x-filament::icon-button icon="heroicon-o-star" :color="$member->is_leader ? 'warning' : 'gray'"
            wire:click="toggleLeader({{ $member->id }})"
            tooltip="{{ $member->is_leader ? 'Remove leader status' : 'Set as leader' }}" size="sm" />
          <x-filament::icon-button icon="heroicon-o-trash" color="danger"
            wire:click="mountAction('removeMemberAction', {student: {{ $member->id }}})" tooltip="Remove member"
            size="sm" />
        </div>
      </div>
    @empty
      <div class="flex flex-col items-center justify-center py-8 sm:py-12">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-100 flex items-center justify-center mb-3 sm:mb-4">
          <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">No members yet</h3>
        <p class="text-xs sm:text-sm text-gray-500 text-center px-4">Add members to this research group</p>
      </div>
    @endforelse
  </div>
  <x-filament-actions::modals />
</div>
