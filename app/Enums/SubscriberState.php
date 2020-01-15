<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Active()
 * @method static static Unsubscribed()
 * @method static static Junk()
 * @method static static Bounced()
 * @method static static Unconfirmed()
 */
final class SubscriberState extends Enum
{
    const Active = 1;
    const Unsubscribed = 2;
    const Junk = 3;
    const Bounced = 4;
    const Unconfirmed = 5;
}
