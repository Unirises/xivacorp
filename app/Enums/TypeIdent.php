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
}
