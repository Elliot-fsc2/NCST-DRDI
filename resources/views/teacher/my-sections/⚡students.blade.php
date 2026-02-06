<?php

use App\Models\Section;
use Livewire\Component;

new class extends Component {
  public Section $section;

  public function mount(Section $section): void
  {
    $this->section = $section->load('students');
  }
};
?>

<div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
  <table class="w-full text-left">
    <thead class="bg-gray-50/50">
      <tr>
        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Student Name</th>
        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
          Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-50">
      @forelse($section->students as $student)
        <tr class="hover:bg-gray-50/30 transition-colors">
          <td class="px-6 py-4 flex items-center space-x-3">
            <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ $student->name }}" alt="">
            <span class="text-sm font-semibold text-gray-800">{{ $student->name }}</span>
          </td>
          <td class="px-6 py-4">
            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Enrolled</span>
          </td>
          <td class="px-6 py-4 text-right">
            <button class="text-gray-400 hover:text-blue-600 font-bold text-xs transition-colors">View
              Profile</button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="p-8 text-center text-gray-400">No students registered.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>