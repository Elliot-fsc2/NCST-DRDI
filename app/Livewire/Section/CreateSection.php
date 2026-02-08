<?php

namespace App\Livewire\Section;

use App\Enums\InstructorRole;
use App\Models\Course;
use App\Models\Section;
use App\Models\Semester;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateSection extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('course_id')
                    ->label('Course')
                    ->options(function (): array {
                        $query = Course::query();
                        if (auth()->user()->profile->role !== InstructorRole::RDO) {  // Changed from !== InstructorRole::RDO->value
                            $query->whereRelation('department', 'id', auth()->user()->profile->department_id);
                        }

                        return $query->pluck('name', 'id')->toArray();
                    }),
                Select::make('semester_id')
                    ->options(Semester::currentSemester()->pluck('year', 'id'))
                    ->required(),
            ])
            ->statePath('data')
            ->model(Section::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $data['teacher_id'] = auth()->user()->profile->id;

        $record = Section::create($data);

        $this->form->model($record)->saveRelationships();
        $this->form->fill();
        $this->dispatch('section-created');
        Notification::make()
            ->title('Section created successfully.')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.section.create-section');
    }
}
