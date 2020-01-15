<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DATE()
 * @method static static NUMBER()
 * @method static static TEXT()
 * @method static static BOOLEAN()
 */
final class FieldType extends Enum
{
    const DATE = 1;
    const NUMBER = 2;
    const TEXT = 3;
    const BOOLEAN = 4;
}
