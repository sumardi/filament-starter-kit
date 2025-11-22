<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Override;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput as BasePhoneInput;

final class PhoneInput extends BasePhoneInput
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->enableIpLookup();
    }
}
