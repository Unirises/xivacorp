<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserRole extends Enum
{
    const Admin =   0;
    const HCP =   1;
    const HR = 2;
    const Clinic = 3;
    const Employee = 4;
    const CoAdmin = 5;

    public static function getDescription($value): string
    {
        if ($value === self::HR) {
            return 'Management';
        }
    
        return parent::getDescription($value);
    }
}
