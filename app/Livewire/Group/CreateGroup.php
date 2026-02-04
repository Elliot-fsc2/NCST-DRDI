<?php

namespace App\Livewire\Group;

use App\Models\ResearchGroup;
use App\Models\Section;
use App\Models\Student;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
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
                Hidden::make('section_id')
                    ->default($this->section?->id),

                TextEntry::make('section.name')
                    ->label('Section')
                    ->default($this->section?->name ?? 'Not specified'),

                // TextInput::make('name')
                //     ->label('Group Name')
                //     ->required()
                //     ->maxLength(255)
                //     ->placeholder('e.g., Research Group Alpha'),

                Select::make('leader_id')
                    ->label('Group Leader')
                    ->options(fn (Get $get): array => Student::query()
                        ->whereHas('sections', fn ($query) => $query->where('sections.id', $get('section_id')))
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->required()
                    ->helperText('Select the student who will lead this research group'),

                Repeater::make('student_ids')
                    ->label('Group Members')
                    ->simple(
                        Select::make('student_id')
                            ->label('Student')
                            ->options(fn (Get $get): array => Student::query()
                                ->whereHas('sections', fn ($query) => $query->where('sections.id', $get('../../section_id')))
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->required()
                            ->distinct()
                            ->disableOptionWhen(fn (string $value, Get $get): bool => $value === $get('../../leader_id'))
                    )
                    ->minItems(1)
                    ->maxItems(10)
                    ->helperText('Select additional members (excluding the leader)')
                    ->addActionLabel('Add Member')
                    ->defaultItems(0),
            ])
            ->statePath('data')
            ->model(ResearchGroup::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            // Extract student IDs from the repeater
            $studentIds = $data['student_ids'] ?? [];
            unset($data['student_ids']);

            // Create the research group with section_id and leader_id
            $researchGroup = ResearchGroup::create([
                'section_id' => $data['section_id'],
                'leader_id' => $data['leader_id'],
            ]);

            // Attach the leader to the group
            $researchGroup->students()->attach($data['leader_id']);

            // Filter out the leader from member IDs to avoid duplicates
            $memberIds = array_filter($studentIds, fn ($id) => $id != $data['leader_id']);

            // Attach other members to the group
            if (! empty($memberIds)) {
                $researchGroup->students()->attach($memberIds);
            }
        });

        // Clear the form
        $this->form->fill([
            'section_id' => $this->section?->id,
        ]);

        // Dispatch browser event to refresh the page or show success message
        $this->dispatch('group-created');

        // Show success notification
        session()->flash('success', 'Research group created successfully!');
    }

    public function render(): View
    {
        return view('livewire.group.create-group');
    }
}
