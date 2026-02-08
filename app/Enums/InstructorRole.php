<?php

namespace App\Enums;

enum InstructorRole: string
{
    case TEACHER = 'teacher';
    case RDO = 'rdo';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::RDO => __('RDO'),
            self::TEACHER => __('Teacher'),
        };
    }
}
