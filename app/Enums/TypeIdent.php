<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TypeIdent extends Enum
{
    const HCP =   0;
    const Tests =   1;
    const Services = 2;

    public static function getDescription($value): string
    {
        if ($value === self::Tests) {
            return 'Medical Tests';
        } else if ($value === self::Services) {
            return 'Medical Services';
        } else if ($value === self::HCP) {
            return 'HCP Types';
        }
    
        return parent::getDescription($value);
    }
}
