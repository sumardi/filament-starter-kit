<?php

declare(strict_types=1);

namespace App\Models;

use Override;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

final class Activity extends SpatieActivity
{
    #[Override]
    protected static function boot(): void
    {
        parent::boot();
    }
}
