<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student Information')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('course_id')
                            ->relationship('course', 'name')
                            ->required(),
                        TextInput::make('student_number')
                            ->required(),
                    ]),
                
                Section::make('User Account')
                    ->schema([
                        TextInput::make('user.email')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: true),
                        TextInput::make('user.password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->minLength(8),
                    ])
                    ->description('Create login credentials for this student'),
            ]);
    }
}
