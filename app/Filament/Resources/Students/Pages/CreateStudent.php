<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Extract user data
        $userData = $data['user'] ?? [];
        unset($data['user']);

        // Create the student
        $student = static::getModel()::create($data);

        // Create the user if user data is provided
        if (! empty($userData)) {
            $student->user()->create([
                'name' => $student->name,
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role' => 'student',
            ]);
        }

        return $student;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
