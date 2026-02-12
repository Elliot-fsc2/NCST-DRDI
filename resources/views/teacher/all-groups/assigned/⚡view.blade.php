<?php

use Livewire\Component;
use App\Models\ResearchGroup;

new class extends Component {
  public ResearchGroup $group;

  public function mount(ResearchGroup $group): void
  {
    abort_unless(
      $group->personnelAssignments()->where('teacher_id', auth()->user()->profile->id)->exists(),
      403
    );

    $this->group->loadCount('members');
    $this->group->load(['members', 'course', 'section.semester']);
  }

  public function title(): string
  {
    return $this->group->name;
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
      <a href="{{ route('teacher.all-groups.view') }}" wire:navigate class="hover:text-blue-600 transition-colors">
        Assigned Groups
      </a>
      <span class="mx-1 sm:mx-2">/</span>
      <span class="text-gray-900">{{ $group->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ $group->name }}</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $group->course->name }} • {{ $group->section->name }}</p>
      </div>
    </div>
  </div>

  {{-- Main Content --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">

    {{-- Members Section --}}
    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 p-4 sm:p-6 lg:p-8 shadow-sm">

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
              class="flex items-center gap-2 sm:gap-3 lg:gap-4 p-3 sm:p-4 bg-gray-50 rounded-xl sm:rounded-2xl">
              <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white shadow-sm"
                src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random&color=fff&size=128"
                alt="{{ $member->name }}">
              <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-sm sm:text-base text-gray-900 truncate">{{ $member->name }}</h3>
                <p class="text-xs sm:text-sm text-gray-500">Member</p>
              </div>
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
            </div>
          @empty
            <div class="flex flex-col items-center justify-center py-8 sm:py-12">
              <div
                class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-100 flex items-center justify-center mb-3 sm:mb-4">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </div>
              <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">No members yet</h3>
              <p class="text-xs sm:text-sm text-gray-500 text-center px-4">This group has no members</p>
            </div>
          @endforelse
        </div>

      </div>
    </div>

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
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $group->section->name }}</p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Course</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $group->course->name }}</p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Semester</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">
              {{ $group->section->semester->year . ' ' . $group->section->semester->phase }}
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

</div>