<?php

use App\Models\Course;
use App\Models\Section;
use App\Models\Semester;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\Attributes\Computed;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

new class extends Component implements HasActions, HasSchemas {
  use InteractsWithActions;
  use InteractsWithSchemas;
  public Section $section;

  public function mount(Section $section)
  {
    $this->section = $section->loadCount(['students', 'researchgroups']);
  }

  #[Computed]
  public function activeTab(): string
  {
    return request()->query('tab', 'news');
  }

  public function editAction(): Action
  {
    return Action::make('editSection')
      ->fillForm([
        'name' => $this->section->name,
        'course_id' => $this->section->course_id,
        'semester_id' => $this->section->semester_id,
      ])
      ->action(function (array $data) {
        $this->section->update($data);
        $this->section->loadCount(['students', 'researchgroups']);
      })
      ->successNotificationTitle('Section updated successfully.')
      ->schema([
        TextInput::make('name')
          ->required(),
        Select::make('course_id')
          ->label('Course')
          ->options(Course::whereRelation('department', 'id', auth()->user()->profile->department_id)->pluck('name', 'id'))
          ->required(),
        Select::make('semester_id')
          ->options(Semester::pluck('name', 'id'))
          ->required(),
      ]);
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
        <button wire:click="mountAction('editAction')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-sm">
          Edit Details
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
          <livewire:teacher::my-sections.news />
        @endif

        @if($this->activeTab === 'groups')
          <livewire:teacher::my-sections.groups :section="$this->section" />
        @endif

        @if($this->activeTab === 'students')
          <livewire:teacher::my-sections.students :section="$this->section" />
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
          <p class="text-[10px] font-black uppercase tracking-widest mb-2">Subject Course</p>
          <p class="text-sm font-bold text-blue-600">{{ $section->course->name ?? 'Course Not Set' }}</p>
        </div>
      </div>
    </div>
  </div>

  <x-filament-actions::modals />
</div>