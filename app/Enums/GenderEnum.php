<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class GenderEnum extends Enum
{
    const Male =   1;
    const Female =   2;
    const Others = 3;
}
