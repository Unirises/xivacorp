<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ServiceType extends Enum
{
    const Consultation =   0;
    const Test =   1;
    const Service =   2;
}
