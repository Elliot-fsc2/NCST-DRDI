<?php

use App\Models\ResearchGroup;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('My Groups')]
  class extends Component {

  public function mount()
  {
    // Auth::loginUsingId(2);
  }

  #[Computed]
  public function activeTab(): string
  {
    return request()->query('tab', 'my-groups');
  }

  #[Computed]
  public function myGroups()
  {
    $teacher = auth()->user()->profile;

    return ResearchGroup::query()
      ->whereHas('section', fn($q) => $q->where('teacher_id', $teacher->id))
      ->with(['section.course', 'section.semester', 'members'])
      ->withCount(['members', 'personnelAssignments'])
      ->latest()
      ->get();
  }

  #[Computed]
  public function assignedGroups()
  {
    $teacher = auth()->user()->profile;

    return ResearchGroup::query()
      ->whereHas('personnelAssignments', fn($q) => $q->where('teacher_id', $teacher->id))
      ->with(['section.course', 'section.semester', 'members', 'personnelAssignments' => fn($q) => $q->where('teacher_id', $teacher->id)])
      ->withCount(['members', 'personnelAssignments'])
      ->latest()
      ->get();
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
      <a href="{{ route('teacher.home') }}" wire:navigate class="hover:text-blue-600 transition-colors">
        Home
      </a>
      <span class="mx-1 sm:mx-2">/</span>
      <span class="text-gray-900">All Groups</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">All Groups</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage all your research groups</p>
      </div>
    </div>
  </div>

  {{-- Tabs Navigation --}}
  <div class="flex items-center space-x-8 border-b border-gray-100 mb-6 sm:mb-8">
    <a href="?tab=my-groups" wire:navigate
      class="pb-4 text-sm font-bold uppercase tracking-widest transition-all {{ $this->activeTab === 'my-groups' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
      My Groups
    </a>
    <a href="?tab=assigned" wire:navigate
      class="pb-4 text-sm font-bold uppercase tracking-widest transition-all {{ $this->activeTab === 'assigned' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
      Assigned to Me
    </a>
  </div>

  {{-- Main Content --}}
  <div>
    @if($this->activeTab === 'my-groups')
      {{-- My Groups --}}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($this->myGroups as $group)
          <a href="{{ route('teacher.my-sections.groups.view', ['section' => $group->section_id, 'group' => $group->id]) }}"
            wire:navigate wire:key="group-{{ $group->id }}">
            <div
              class="group relative bg-gradient-to-br from-white to-gray-50 rounded-3xl border border-gray-200 overflow-hidden hover:shadow-xl hover:border-blue-200 transition-all duration-300 hover:-translate-y-1">
              {{-- Decorative gradient bar --}}
              <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500">
              </div>

              <div class="p-6">
                {{-- Header --}}
                <div class="flex justify-between items-start mb-5">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <div>
                        <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $group->name }}</h4>
                        <p class="text-xs text-gray-500 font-medium">{{ $group->section->course->name }}</p>
                      </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $group->section->name }}</p>
                  </div>
                </div>

                {{-- Stats badges --}}
                <div class="flex items-center gap-2 mb-4">
                  <div
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>{{ $group->members_count }} {{ Str::plural('Member', $group->members_count) }}</span>
                  </div>
                  <div
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold">
                    <span>{{ $group->personnel_assignments_count }}
                      {{ Str::plural('Personnel', $group->personnel_assignments_count) }}</span>
                  </div>
                </div>

                {{-- Member avatars --}}
                <div class="pt-4 border-t border-gray-100">
                  <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Team Members</p>
                  @if($group->members->count() > 0)
                    <div class="flex items-center gap-2">
                      <div class="flex -space-x-3">
                        @foreach($group->members->take(4) as $member)
                          <div class="relative group/avatar">
                            <img
                              class="w-10 h-10 rounded-full border-3 border-white ring-2 ring-gray-100 transition-transform hover:scale-110 hover:z-10"
                              src="https://ui-avatars.com/api/?name={{ urlencode($member->student_name ?? 'Member') }}&background=random&color=fff&size=128"
                              alt="{{ $member->student_name ?? 'Member' }}" title="{{ $member->student_name ?? 'Member' }}">
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
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No groups found</h3>
            <p class="text-sm text-gray-500">Create a group in your sections</p>
          </div>
        @endforelse
      </div>
    @endif

    @if($this->activeTab === 'assigned')
      {{-- Assigned to Me --}}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($this->assignedGroups as $group)
          <a href="{{ route('teacher.my-sections.groups.view', ['section' => $group->section_id, 'group' => $group->id]) }}"
            wire:navigate wire:key="assigned-group-{{ $group->id }}">
            <div
              class="group relative bg-gradient-to-br from-white to-gray-50 rounded-3xl border border-gray-200 overflow-hidden hover:shadow-xl hover:border-blue-200 transition-all duration-300 hover:-translate-y-1">
              {{-- Decorative gradient bar --}}
              <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500">
              </div>

              <div class="p-6">
                {{-- Header --}}
                <div class="flex justify-between items-start mb-4">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <div>
                        <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $group->name }}</h4>
                        <p class="text-xs text-gray-500 font-medium">{{ $group->section->course->name }}</p>
                      </div>
                    </div>
                    <div class="flex flex-wrap gap-1 mb-2">
                      @foreach($group->personnelAssignments as $assignment)
                        <span
                          class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 rounded-lg text-xs font-semibold">
                          {{ $assignment->role->getLabel() }}
                        </span>
                      @endforeach
                    </div>
                    <p class="text-xs text-gray-400">{{ $group->section->name }}</p>
                  </div>
                </div>

                {{-- Stats badges --}}
                <div class="flex items-center gap-2 mb-4">
                  <div
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>{{ $group->members_count }} {{ Str::plural('Member', $group->members_count) }}</span>
                  </div>
                  <div
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold">
                    <span>{{ $group->personnel_assignments_count }}
                      {{ Str::plural('Personnel', count: $group->personnel_assignments_count) }}</span>
                  </div>
                </div>

                {{-- Member avatars --}}
                <div class="pt-4 border-t border-gray-100">
                  <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Team Members</p>
                  @if($group->members->count() > 0)
                    <div class="flex items-center gap-2">
                      <div class="flex -space-x-3">
                        @foreach($group->members->take(4) as $member)
                          <div class="relative group/avatar">
                            <img
                              class="w-10 h-10 rounded-full border-3 border-white ring-2 ring-gray-100 transition-transform hover:scale-110 hover:z-10"
                              src="https://ui-avatars.com/api/?name={{ urlencode($member->student_name ?? 'Member') }}&background=random&color=fff&size=128"
                              alt="{{ $member->student_name ?? 'Member' }}" title="{{ $member->student_name ?? 'Member' }}">
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
                class="absolute inset-0 bg-gradient-to-br from-green-500/0 to-emerald-500/0 group-hover:from-green-500/5 group-hover:to-emerald-500/5 pointer-events-none transition-all duration-300">
              </div>
            </div>
          </a>
        @empty
          <div class="col-span-full flex flex-col items-center justify-center py-16 px-6">
            <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
              <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No assignments found</h3>
            <p class="text-sm text-gray-500">You haven't been assigned to any groups yet</p>
          </div>
        @endforelse
      </div>
    @endif
  </div>
</div>
