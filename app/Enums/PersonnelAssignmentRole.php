<?php

namespace App\Enums;

enum PersonnelAssignmentRole: string
{
    case TECHNICAL_ADVISER = 'technical_adviser';
    case LANGUAGE_CRITIC = 'language_critic';
    case GRAMMARIAN = 'grammarian';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TECHNICAL_ADVISER => __('Technical Adviser'),
            self::LANGUAGE_CRITIC => __('Language Critic'),
            self::GRAMMARIAN => __('Grammarian'),
        };
    }
}
