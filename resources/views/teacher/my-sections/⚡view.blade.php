<?php

use App\Models\Section;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
  public Section $section;

  public function mount(Section $section)
  {
    $this->section = $section
      ->load(['researchgroups' => fn($query) => $query->withCount('students')->with('students')])
      ->loadCount(['students', 'researchgroups']);
  }

  #[Computed]
  public function activeTab(): string
  {
    return request()->query('tab', 'news');
  }
};
?>

@assets
<link href="{{ Vite::asset('resources/css/filament/app.css') }}" rel="stylesheet">
@endassets

<div class="max-w-7xl mx-auto px-4 py-1 font-interface">

  <div class="mb-8">
    <nav class="flex text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
      <a href="{{ route('teacher.my-sections') }}" wire:navigate class="hover:text-blue-600 transition-colors">My
        Sections</a>
      <span class="mx-2">/</span>
      <span class="text-gray-900">{{ $section->name }}</span>
    </nav>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $section->name }}</h1>
      <div class="flex items-center space-x-3">
        <button
          class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-all">
          Edit Details
        </button>
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
          Post Update
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div class="lg:col-span-3">
      <div class="flex items-center space-x-8 border-b border-gray-100 mb-8">
        <a href="?tab=news" wire:navigate
          class="pb-4 text-sm font-bold uppercase tracking-widest transition-all {{ $this->activeTab === 'news' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
          News Feed
        </a>
        <a href="?tab=groups" wire:navigate
          class="pb-4 text-sm font-bold uppercase tracking-widest transition-all {{ $this->activeTab === 'groups' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
          Groups
        </a>
        <a href="?tab=students" wire:navigate
          class="pb-4 text-sm font-bold uppercase tracking-widest transition-all {{ $this->activeTab === 'students' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
          Students
        </a>
      </div>

      <div class="space-y-6">
        @if($this->activeTab === 'news')
          <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm">
            <div class="flex items-center space-x-4 mb-6">
              <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                  stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.357.205a.75.75 0 0 1-1.1-.512l-.814-2.406m5.13-9.18c.31.1.615.22.91.36m-3.14 8.82c.31-.1.615-.22.91-.36M18.342 6.345a9.755 9.755 0 0 1 2.378 3.965m-2.378 7.345a9.755 9.755 0 0 0 2.378-3.965m-2.378 3.965a9.755 9.755 0 0 1-2.28-4.695m2.28 4.695c-.492.308-1.018.574-1.577.798m1.577-8.838a9.755 9.755 0 0 0-2.28 4.695m0 0a9.755 9.755 0 0 1-2.28-4.695m0 0c-.492.308-1.018.574-1.577.798" />
                </svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-900">Weekly Announcement</h4>
                <p class="text-xs text-gray-400 font-medium">Posted 2 hours ago</p>
              </div>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed mb-6">
              Welcome to the new semester! Please ensure all group members are registered by the end of the week. We
              will be starting our first module on Advanced Physics tomorrow at 8:00 AM.
            </p>
            <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between">
              <span class="text-xs font-bold text-gray-500">Module_1_Intro.pdf</span>
              <button class="text-blue-600 text-xs font-bold">Download</button>
            </div>
          </div>
        @endif

        @if($this->activeTab === 'groups')
          <div class="mb-6 flex justify-end">
            <x-filament::modal width="xl">
              <x-slot name="trigger">
                <x-filament::button
                  class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                  Create New Group
                </x-filament::button>
              </x-slot>
              <x-slot name="heading">
                Create Research Group
              </x-slot>
              <livewire:group.create-group :section="$this->section" />
            </x-filament::modal>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($this->section->researchgroups as $group)
              <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                  <div>
                    <h4 class="font-bold text-gray-900">Group {{ $loop->iteration }}</h4>
                    <p class="text-xs text-gray-500 mt-1">{{ $group->students_count }} members</p>
                  </div>
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
        @endif

        @if($this->activeTab === 'students')
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
                @forelse($this->section->students as $student)
                  <tr class="hover:bg-gray-50/30 transition-colors">
                    <td class="px-6 py-4 flex items-center space-x-3">
                      <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ $student->name }}" alt="">
                      <span class="text-sm font-semibold text-gray-800">{{ $student->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                      <span
                        class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Enrolled</span>
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
        @endif
      </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm">
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Section
          Stats</h3>

        <div class="space-y-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                  stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
              </div>
              <span class="text-sm font-semibold text-gray-600">Students</span>
            </div>
            <span class="text-sm font-bold text-gray-900">{{ $section->students_count }}</span>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                  stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a5.971 5.971 0 0 0-.941 3.197m0 0a5.971 5.971 0 0 0-.941 3.197m0 0-.001.031c0 .225.012.447.037.666A11.944 11.944 0 0 0 12 21c2.17 0 4.207-.576 5.963-1.584A6.062 6.062 0 0 0 18 18.719m-12 0a5.971 5.971 0 0 0 .941-3.197m0 0A5.995 5.995 0 0 1 12 12.75a5.995 5.995 0 0 1 5.058 2.772m0 0a5.971 5.971 0 0 1 .941 3.197M12 10.5a3.375 3.375 0 1 0 0-6.75 3.375 3.375 0 0 0 0 6.75ZM4.5 9.75a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM15 9.75a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0Z" />
                </svg>
              </div>
              <span class="text-sm font-semibold text-gray-600">Groups</span>
            </div>
            <span class="text-sm font-bold text-gray-900">{{ $section->researchgroups_count }}</span>
          </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-50">
          <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-2">Subject Course</p>
          <p class="text-sm font-bold text-blue-600">{{ $section->course->name ?? 'Course Not Set' }}</p>
        </div>
      </div>
    </div>
  </div>
</div>