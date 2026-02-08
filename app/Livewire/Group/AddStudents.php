<?php

namespace App\Livewire\Group;

use App\Models\Section;
use App\Services\SectionStudentService;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AddStudents extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public Section $section;

    public function mount(Section $section): void
    {
        $this->section = $section;
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('students')
                    ->addActionLabel('Add Student')
                    ->schema([
                        TextInput::make('name')
                            ->columnSpan(1)->required(),
                        TextInput::make('email')
                            ->columnSpan(1)->email(),
                        TextInput::make('contact_number')
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    public function submit(SectionStudentService $sectionStudentService): void
    {
        $data = $this->form->getState();
        $data['section_id'] = $this->section->id;
        $sectionStudentService->addStudents($data);
        Notification::make()
            ->title('Students added successfully')
            ->success()
            ->send();
        $this->redirect(route('teacher.my-sections.view', ['section' => $this->section, 'tab' => 'students']), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.group.add-students');
    }
}
