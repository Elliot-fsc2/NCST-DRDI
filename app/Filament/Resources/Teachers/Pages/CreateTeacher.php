<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Extract user data
        $userData = $data['user'] ?? [];
        unset($data['user']);

        // Create the teacher
        $teacher = static::getModel()::create($data);

        // Create the user if user data is provided
        if (! empty($userData)) {
            $teacher->user()->create([
                'name' => $teacher->name,
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role' => 'teacher',
            ]);
        }

        return $teacher;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
