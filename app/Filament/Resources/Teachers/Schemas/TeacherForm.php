<?php

namespace App\Filament\Resources\Teachers\Schemas;

use App\Enums\InstructorRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Teacher Information')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required(),
                        Select::make('role')
                            ->options(InstructorRole::class),
                    ]),

                Section::make('User Account')
                    ->schema([
                        TextInput::make('user.email')
                            ->email()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->unique('users', 'email', ignoreRecord: true),
                        TextInput::make('user.password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->minLength(8),
                    ])
                    ->description('Create login credentials for this teacher'),
            ]);
    }
}
