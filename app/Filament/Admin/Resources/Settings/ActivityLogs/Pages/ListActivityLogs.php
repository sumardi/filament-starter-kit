<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\ActivityLogs\Pages;

use App\Filament\Admin\Resources\Settings\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

final class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
