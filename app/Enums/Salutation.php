<?php

namespace App\Enums;

enum Salutation: string
{
    case DR = 'dr';
    case PROF_DR = 'prof_dr';
    case MR = 'mr';
    case MS = 'ms';

    public function label(): string
    {
        return match ($this) {
            self::DR => 'Dr.',
            self::PROF_DR => 'Prof. Dr.',
            self::MR => 'Mr.',
            self::MS => 'Ms.',
        };
    }
}
