<?php

use App\Models\Section;
use Livewire\Component;
use Filament\Schemas\Schema;
use App\Models\SectionStudent;
use Livewire\Attributes\Title;
use App\Services\SectionStudentService;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;



new #[Title('Edit Student')]
  class extends Component implements HasActions, HasSchemas {
  use InteractsWithActions;
  use InteractsWithSchemas;

  public ?array $data = [];
  public SectionStudent $student;
  public Section $section;

  public function mount(Section $section, SectionStudent $student): void
  {
    abort_unless($section->teacher_id === auth()->user()->profile->id, 403);
    abort_unless($student->section_id === $section->id, 404);

    $this->form->fill([
      'name' => $this->student->student_name,
      'email' => $this->student->email,
      'contact_number' => $this->student->contact_number
    ]);
  }

  public function form(Schema $schema): Schema
  {
    return $schema
      ->components([
        TextInput::make('name')
          ->required(),
        TextInput::make('email')
          ->email(),
        TextInput::make('contact_number')
      ])
      ->statePath('data');
  }

  public function update(SectionStudentService $sectionStudentService): void
  {
    $data = $this->form->getState();
    $data['id'] = $this->student->id;
    $sectionStudentService->updateStudents($data);
    Notification::make()
      ->title('Student updated successfully')
      ->success()
      ->send();
    $this->redirect(route('teacher.my-sections.view', ['section' => $this->section, 'tab' => 'students']), navigate: true);
  }
};
?>


@assets
<link href="{{ Vite::asset('resources/css/filament/app.css') }}" rel="stylesheet">
@endassets
<div class="max-w-7xl mx-auto px-4 py-1 font-interface">
  <div class="mb-8">
    <nav class="flex text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
      <a href="{{ route('teacher.my-sections') }}" wire:navigate class="hover:text-blue-600 transition-colors">
        My Sections
      </a>
      <span class="mx-2">/</span>
      <a href="{{ route('teacher.my-sections.view', $section) }}" wire:navigate
        class="hover:text-blue-600 transition-colors">
        {{ $section->name }}
      </a>
      <span class="mx-2">/</span>
      <span class="text-gray-900">Edit Student</span>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Student in {{ $section->name }}</h1>
  </div>

  <div class="max-w-2xl rounded-4xl border border-gray-100 py-5 px-10 shadow-sm">
    <x-filament::section>

      {{-- Content --}}
      <form wire:submit="update">
        {{ $this->form }}
        <x-filament::button class="mt-10 w-full bg-blue-500" type="submit">Update</x-filament::button>
      </form>
    </x-filament::section>
  </div>
</div>
