<?php

namespace App\Enums;

enum TagType: int
{
    case Unknown = 0;
    case Color = 1;
    case Size = 2;
    case Material = 3;
    case Brand = 4;
    case Style = 5;

    public function label(): string
    {
        return match ($this) {
            self::Color => __('Color'),
            self::Size => __('Size'),
            self::Material => __('Material'),
            self::Brand => __('Brand'),
            self::Style => __('Style'),
            self::Unknown => __('Unknown'),
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'label', 'value');
    }
}

