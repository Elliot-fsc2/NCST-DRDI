<?php

namespace App\Livewire\Group;

use App\Models\ResearchGroup;
use App\Models\Section;
use App\Models\Student;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateGroup extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public ?Section $section = null;

    public function mount(?Section $section = null): void
    {
        $this->section = $section;

        $this->form->fill([
            'section_id' => $section?->id,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('section.name')
                    ->label('Section')
                    ->default($this->section?->name ?? 'Not specified'),

                Repeater::make('members')
                    ->label('Group Members')
                    ->schema([
                        Select::make('student_id')
                            ->label('Student')
                            ->options(fn (Get $get): array => Student::query()
                                ->whereHas('sections', fn ($query) => $query->where('sections.id', $get('../../section_id')))
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->required()
                            ->distinct(),

                        Checkbox::make('is_leader')
                            ->label('Leader')
                            ->fixIndistinctState()
                            ->inline(false),
                    ])
                    ->columns(2)
                    ->minItems(1)
                    ->maxItems(10)
                    ->helperText('Add group members and check one as the leader')
                    ->addActionLabel('Add Member')
                    ->defaultItems(1),
            ])
            ->statePath('data')
            ->model(ResearchGroup::class);
    }

    public function create(\App\Actions\CreateGroup $action): void
    {
        $data = collect($this->form->getState());

        $members = collect($data['members'] ?? []);

        $transformedData = [
            'section_id' => $this->section->id,
            'leader_id' => $members->firstWhere('is_leader', true)['student_id'] ?? null,
            'student_ids' => $members->pluck('student_id')->all(),
        ];
        // dd($transformedData);
        $action->handle($transformedData);

        // Clear the form
        $this->form->fill([
            'section_id' => $this->section?->id,
        ]);

        $this->dispatch('group-created');
        $this->dispatch('close-modal', id: 'create-group');

        Notification::make()
            ->title('Research group created successfully.')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.group.create-group');
    }
}
