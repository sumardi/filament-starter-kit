<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as BaseModel;

/**
 * @property bool $is_deletable
 * @property bool $is_editable
 */
final class Role extends BaseModel
{
    use CausesActivity;

    /** @use HasFactory<RoleFactory> */
    use HasFactory;

    use LogsActivity;

    /**
     * Get the options for activity logging.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'display_name', 'guard_name', 'is_deletable', 'is_editable'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_deletable' => 'boolean',
            'is_editable' => 'boolean',
        ];
    }
}
