<?php

use App\Models\Section;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

new class extends Component implements HasActions, HasSchemas {
  use InteractsWithActions;
  use InteractsWithSchemas;
  public Section $section;

  public function mount(Section $section): void
  {
    $this->section = $section->load('students');
  }

};
?>

<div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
  <div class="p-3 sm:p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <h3 class="text-sm font-bold text-gray-700">Students</h3>
    <div>
      <x-filament::button tag="a" href="{{ route('teacher.my-sections.students', ['section' => $this->section]) }}"
        wire:navigate
        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm">
        <span class="sm:hidden">+ Add</span>
        <span class="hidden sm:inline">+ Add Student</span>
      </x-filament::button>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left">
      <thead class="bg-gray-50/50">
        <tr>
          <th class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">Student
            Name</th>
          <th
            class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">
            Email</th>
          <th
            class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">
            Contact Number</th>
          <th class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">
            Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($section->students as $student)
          <tr class="hover:bg-gray-50/30 transition-colors">
            <td class="px-3 sm:px-6 py-3 sm:py-4">
              <div class="flex items-center space-x-2 sm:space-x-3">
                <img class="w-6 h-6 sm:w-8 sm:h-8 rounded-full"
                  src="https://ui-avatars.com/api/?name={{ urlencode($student->student_name) }}"
                  alt="{{ $student->student_name }}">
                <div class="min-w-0">
                  <span
                    class="text-xs sm:text-sm font-semibold text-gray-800 block truncate">{{ $student->student_name }}</span>
                  <span class="text-xs text-gray-500 block md:hidden truncate">{{ $student->email }}</span>
                </div>
              </div>
            </td>
            <td class="px-3 sm:px-6 py-3 sm:py-4 hidden md:table-cell">
              <span class="text-sm text-gray-600 block truncate">{{ $student->email }}</span>
            </td>
            <td class="px-3 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
              <span class="text-sm text-gray-600">{{ $student->contact_number }}</span>
            </td>
            <td class="px-3 sm:px-6 py-3 sm:py-4 text-right">
              <div class="flex items-center justify-end gap-1 sm:gap-2">
                <button class="text-blue-600 hover:text-blue-700 font-semibold text-xs transition-colors">
                  Edit
                </button>
                <button class="text-red-600 hover:text-red-700 font-semibold text-xs transition-colors">
                  Delete
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="p-6 sm:p-8 text-center text-gray-400 text-sm">No students registered.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>