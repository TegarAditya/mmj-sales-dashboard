<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SemesterEnum implements HasLabel, HasIcon, HasColor
{
    case GANJIL;
    case GENAP;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::GANJIL => 'Ganjil',
            self::GENAP => 'Genap',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::GANJIL => 'heroicon-o-arrow-up-circle',
            self::GENAP => 'heroicon-o-arrow-down-circle',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::GANJIL => 'primary',
            self::GENAP => 'success',
        };
    }
}
