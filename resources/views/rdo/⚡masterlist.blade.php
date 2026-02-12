<?php

use App\Models\Course;
use App\Models\Department;
use App\Models\ResearchGroup;
use App\Models\Section;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

new #[Title('Group Masterlist')]
  class extends Component {

  #[Url]
  public ?int $department_id = null;

  #[Url]
  public ?int $teacher_id = null;

  #[Url]
  public ?int $section_id = null;

  #[Url]
  public ?int $course_id = null;

  public function updatedDepartmentId()
  {
    $this->teacher_id = null;
    $this->section_id = null;
    $this->course_id = null;
  }

  public function updatedTeacherId()
  {
    $this->section_id = null;
  }

  public function getTeachersProperty()
  {
    $query = Teacher::query();
    if ($this->department_id) {
      $query->whereHas('sections.course', fn($q) => $q->where('department_id', $this->department_id));
    }
    return $query->get()->pluck('name', 'id');
  }

  public function getCoursesProperty()
  {
    $query = Course::query();
    if ($this->department_id) {
      $query->where('department_id', $this->department_id);
    }
    return $query->get();
  }

  public function getSectionsProperty()
  {
    $query = Section::query()->with('course');
    if ($this->teacher_id) {
      $query->where('teacher_id', $this->teacher_id);
    }
    return $query->get()->mapWithKeys(fn($section) => [
      $section->id => "{$section->name} - {$section->course->name}"
    ]);
  }

  public function getGroupsProperty()
  {
    $query = ResearchGroup::query()
      ->with(['section.teacher.department', 'section.course.department', 'section', 'course', 'members'])
      ->withCount('members');

    if ($this->department_id) {
      $query->whereHas('section.course', fn($q) => $q->where('department_id', $this->department_id));
    }

    if ($this->teacher_id) {
      $query->whereHas('section', fn($q) => $q->where('teacher_id', $this->teacher_id));
    }

    if ($this->section_id) {
      $query->where('section_id', $this->section_id);
    }

    if ($this->course_id) {
      $query->where('course_id', $this->course_id);
    }

    return $query->get();
  }
};
?>
<div class="max-w-7xl mx-auto px-3 sm:px-4 py-1 font-interface">

  <div class="mb-6 sm:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Group Masterlist</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage all research groups across the institution</p>
      </div>
    </div>
  </div>

  {{-- Main Content --}}
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">

    {{-- Left Content --}}
    <div class="lg:col-span-3 space-y-4 sm:space-y-6">

      {{-- Filters --}}
      <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
          <x-heroicon-o-funnel class="w-5 h-5 text-gray-400" />
          <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Filters</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <x-filament::input.wrapper>
              <x-filament::input.select wire:model.live="department_id">
                <option value="">All Departments</option>
                @foreach(Department::all() as $department)
                  <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
              </x-filament::input.select>
            </x-filament::input.wrapper>
          </div>

          <div>
            <x-filament::input.wrapper>
              <x-filament::input.select wire:model.live="teacher_id">
                <option value="">All Teachers</option>
                @foreach($this->teachers as $id => $name)
                  <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
              </x-filament::input.select>
            </x-filament::input.wrapper>
          </div>

          <div>
            <x-filament::input.wrapper>
              <x-filament::input.select wire:model.live="section_id">
                <option value="">All Sections</option>
                @foreach($this->sections as $id => $name)
                  <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
              </x-filament::input.select>
            </x-filament::input.wrapper>
          </div>

          <div>
            <x-filament::input.wrapper>
              <x-filament::input.select wire:model.live="course_id">
                <option value="">All Courses</option>
                @foreach($this->courses as $course)
                  <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
              </x-filament::input.select>
            </x-filament::input.wrapper>
          </div>
        </div>
      </div>

      {{-- Groups Grid --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($this->groups as $group)
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
                      <p class="text-xs text-gray-500 font-medium">Research Group</p>
                    </div>
                  </div>

                  {{-- Section & Course Info --}}
                  <div class="mt-3 space-y-1">
                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                      <x-heroicon-o-academic-cap class="w-3.5 h-3.5" />
                      <span class="font-medium">{{ $group->section->name }}</span>
                      <span class="text-gray-400">•</span>
                      <span>{{ $group->course->name }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                      <x-heroicon-o-user class="w-3.5 h-3.5" />
                      <span>{{ $group->section->teacher->name }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                      <x-heroicon-o-building-office class="w-3.5 h-3.5" />
                      <span>{{ $group->section->teacher->department->name }}</span>
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
                      @foreach($group->members->take(4) as $member)
                        <div class="relative group/avatar">
                          <img
                            class="w-10 h-10 rounded-full border-3 border-white ring-2 ring-gray-100 transition-transform hover:scale-110 hover:z-10"
                            src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random&color=fff&size=128"
                            alt="{{ $member->name }}" title="{{ $member->name }}">
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
        @empty
          <div class="col-span-full flex flex-col items-center justify-center py-16 px-6">
            <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
              <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No groups found</h3>
            <p class="text-sm text-gray-500">Try adjusting your filters</p>
          </div>
        @endforelse
      </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-4 sm:space-y-6">

      {{-- Quick Info Card --}}
      <div
        class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl sm:rounded-3xl border border-blue-100 p-4 sm:p-6 shadow-sm">
        <h3 class="text-xs sm:text-sm font-bold text-gray-900 uppercase tracking-wide sm:tracking-widest mb-3 sm:mb-4">
          Quick Info</h3>
        <div class="space-y-3 sm:space-y-4">
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Groups</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $this->groups->count() }}</p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Members</p>
            <p class="text-sm sm:text-base font-bold text-gray-900">{{ $this->groups->sum('members_count') }}</p>
          </div>
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Active Filters
            </p>
            <p class="text-sm sm:text-base font-bold text-blue-600">
              {{ collect([$department_id, $teacher_id, $section_id, $course_id])->filter()->count() }}
            </p>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>