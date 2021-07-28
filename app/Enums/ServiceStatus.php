<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ServiceStatus extends Enum
{
    const Upcoming =   0;
    const Ongoing =   1;
    const Completed =   2;
}
