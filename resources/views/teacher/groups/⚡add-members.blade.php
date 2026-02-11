<?php

use App\Models\Member;
use App\Models\Section;
use Livewire\Component;
use App\Models\ResearchGroup;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

new class extends Component {
  public ResearchGroup $group;

  public Section $section;

  public function mount(): void
  {
    abort_if($this->group->section_id !== $this->section->id, 404);
    abort_if($this->section->teacher_id !== auth()->user()->profile?->id, 404);

    $this->group->load('members');
  }

  #[Computed]
  public function availableStudents()
  {
    $existingMemberNames = Member::whereHas('researchGroup', function ($query) {
      $query->where('section_id', $this->section->id);
    })->pluck('student_name')->toArray();

    return $this->section->students()
      ->whereNotIn('student_name', $existingMemberNames)
      ->orderBy('student_name')
      ->get();
  }

  public function addMembers(array $selectedStudents, ?int $leaderId): void
  {
    $validator = Validator::make(
      ['selectedStudents' => $selectedStudents],
      [
        'selectedStudents' => 'required|array|min:1',
        'selectedStudents.*' => 'exists:section_students,id',
      ],
      [
        'selectedStudents.required' => 'Please select at least one student to add.',
        'selectedStudents.min' => 'Please select at least one student to add.',
      ]
    );

    if ($validator->fails()) {
      throw ValidationException::withMessages($validator->errors()->toArray());
    }

    $students = $this->section->students()->whereIn('id', $selectedStudents)->get();

    foreach ($students as $student) {
      $this->group->members()->create([
        'student_name' => $student->student_name,
        'is_leader' => $student->id === $leaderId,
      ]);
    }

    session()->flash('success', 'Members added successfully!');

    $this->redirect(route('teacher.my-sections.groups.view', [
      'section' => $this->section->id,
      'group' => $this->group->id,
    ]));
  }
};
?>

@assets
<link href="{{ Vite::asset('resources/css/filament/app.css') }}" rel="stylesheet">
@endassets

<div class="max-w-7xl mx-auto px-3 sm:px-4 py-1 font-interface" x-data="{
    selectedStudents: [],
    leaderId: null,

    toggleStudent(studentId) {
      const index = this.selectedStudents.indexOf(studentId);
      if (index > -1) {
        this.selectedStudents.splice(index, 1);
        if (this.leaderId === studentId) {
          this.leaderId = null;
        }
      } else {
        this.selectedStudents.push(studentId);
      }
    },

    setLeader(studentId) {
      if (!this.selectedStudents.includes(studentId)) {
        this.selectedStudents.push(studentId);
      }
      this.leaderId = studentId;
    },

    isSelected(studentId) {
      return this.selectedStudents.includes(studentId);
    },

    submitMembers() {
      if (this.selectedStudents.length === 0) return;
      $wire.addMembers(this.selectedStudents, this.leaderId);
    }
  }">

  {{-- Breadcrumbs --}}
  <div class="mb-6 sm:mb-8">
    <nav
      class="flex flex-wrap text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide sm:tracking-widest mb-2 gap-x-1 sm:gap-x-0">
      <a href="{{ route('teacher.my-sections') }}" wire:navigate class="hover:text-blue-600 transition-colors">
        My Sections
      </a>
      <span class="mx-1 sm:mx-2">/</span>
      <a href="{{ route('teacher.my-sections.view', ['section' => $section->id, 'tab' => 'groups']) }}" wire:navigate
        class="hover:text-blue-600 transition-colors truncate max-w-[80px] sm:max-w-none">
        {{ $section->name }}
      </a>
      <span class="mx-1 sm:mx-2">/</span>
      <a href="{{ route('teacher.my-sections.groups.view', ['section' => $section, 'group' => $group]) }}" wire:navigate
        class="hover:text-blue-600 transition-colors truncate max-w-[80px] sm:max-w-none">
        {{ $group->name }}
      </a>
      <span class="mx-1 sm:mx-2">/</span>
      <span class="text-gray-900">Add Members</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Add Members</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Select students to add to {{ $group->name }}</p>
      </div>
      <div class="flex items-center gap-2 sm:gap-3">
        <a href="{{ route('teacher.my-sections.groups.view', ['section' => $section, 'group' => $group]) }}"
          wire:navigate
          class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 sm:px-4 py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-sm flex-1 sm:flex-none text-center">
          Cancel
        </a>
        <button @click="submitMembers()" :disabled="selectedStudents.length === 0"
          class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex-1 sm:flex-none">
          <span
            x-text="'Add ' + selectedStudents.length + ' ' + (selectedStudents.length === 1 ? 'Member' : 'Members')"></span>
        </button>
      </div>
    </div>
  </div>

  {{-- Main Content --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">

    {{-- Available Students --}}
    <div class="lg:col-span-2">
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 p-4 sm:p-6 lg:p-8 shadow-sm">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
          <h2 class="text-lg sm:text-xl font-bold text-gray-900">Available Students</h2>
          <span
            class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 bg-gray-100 text-gray-700 rounded-full text-[10px] sm:text-xs font-semibold whitespace-nowrap">
            {{ $this->availableStudents->count() }} Available
          </span>
        </div>

        @if($this->availableStudents->count() > 0)
          <div class="space-y-2">
            @foreach($this->availableStudents as $student)
              <div x-data="{ studentId: {{ $student->id }} }" wire:key="student-{{ $student->id }}"
                @click="toggleStudent(studentId)"
                :class="isSelected(studentId) ? 'bg-blue-50 border-blue-300' : 'bg-gray-50 border-transparent hover:border-gray-200'"
                class="flex items-center gap-2 sm:gap-3 lg:gap-4 p-3 sm:p-4 rounded-xl sm:rounded-2xl border-2 transition-all cursor-pointer active:scale-[0.98]">

                {{-- Checkbox --}}
                <div class="flex-shrink-0">
                  <div :class="isSelected(studentId) ? 'bg-blue-600 border-blue-600' : 'bg-white border-gray-300'"
                    class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all">
                    <svg x-show="isSelected(studentId)" class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>

                {{-- Avatar --}}
                <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white shadow-sm"
                  src="https://ui-avatars.com/api/?name={{ urlencode($student->student_name) }}&background=random&color=fff&size=128"
                  alt="{{ $student->student_name }}">

                {{-- Student Info --}}
                <div class="flex-1 min-w-0">
                  <h3 class="font-semibold text-sm sm:text-base text-gray-900 truncate">{{ $student->student_name }}</h3>
                  <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $student->email }}</p>
                </div>

                {{-- Leader Button --}}
                <button x-show="isSelected(studentId)" @click.stop="setLeader(studentId)"
                  :class="leaderId === studentId ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                  class="flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[10px] sm:text-xs font-semibold transition-all flex-shrink-0">
                  <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  <span class="hidden sm:inline" x-text="leaderId === studentId ? 'Leader' : 'Set as Leader'"></span>
                  <span class="sm:hidden" x-text="leaderId === studentId ? 'Leader' : 'Leader'"></span>
                </button>
              </div>
            @endforeach
          </div>
        @else
          <div class="flex flex-col items-center justify-center py-8 sm:py-12">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-100 flex items-center justify-center mb-3 sm:mb-4">
              <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">No available students</h3>
            <p class="text-xs sm:text-sm text-gray-500 text-center px-4">All students in this section are already members
            </p>
          </div>
        @endif
      </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4 sm:space-y-6">

      {{-- Selection Summary --}}
      <div
        class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl sm:rounded-3xl border border-blue-100 p-4 sm:p-6 shadow-sm">
        <h3 class="text-xs sm:text-sm font-bold text-gray-900 uppercase tracking-wide sm:tracking-widest mb-3 sm:mb-4">
          Selection Summary</h3>
        <div class="space-y-3 sm:space-y-4">
          <div>
            <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Students Selected
            </p>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900" x-text="selectedStudents.length"></p>
          </div>

          <template x-if="leaderId !== null">
            <div class="pt-3 sm:pt-4 border-t border-blue-200">
              <p class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Selected Leader
              </p>
              @foreach($this->availableStudents as $student)
                <div x-show="leaderId === {{ $student->id }}"
                  class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 bg-white rounded-lg sm:rounded-xl">
                  <img class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-yellow-400"
                    src="https://ui-avatars.com/api/?name={{ urlencode($student->student_name) }}&background=random&color=fff&size=128"
                    alt="{{ $student->student_name }}">
                  <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 text-xs sm:text-sm truncate">{{ $student->student_name }}</h4>
                    <p class="text-[10px] sm:text-xs text-gray-500">Group Leader</p>
                  </div>
                </div>
              @endforeach
            </div>
          </template>
        </div>
      </div>

      {{-- Instructions --}}
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 p-4 sm:p-6 shadow-sm">
        <h3 class="text-xs sm:text-sm font-bold text-gray-900 uppercase tracking-wide sm:tracking-widest mb-3 sm:mb-4">
          Instructions</h3>
        <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm text-gray-600">
          <div class="flex items-start gap-2">
            <div
              class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
              <span class="text-[10px] sm:text-xs font-bold text-blue-600">1</span>
            </div>
            <p>Click on students to select them for the group</p>
          </div>
          <div class="flex items-start gap-2">
            <div
              class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
              <span class="text-[10px] sm:text-xs font-bold text-blue-600">2</span>
            </div>
            <p>Optionally designate one member as the group leader</p>
          </div>
          <div class="flex items-start gap-2">
            <div
              class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
              <span class="text-[10px] sm:text-xs font-bold text-blue-600">3</span>
            </div>
            <p>Click "Add Members" to confirm your selection</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>