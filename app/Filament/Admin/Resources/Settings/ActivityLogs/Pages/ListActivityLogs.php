<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\ActivityLogs\Pages;

use Override;
use App\Filament\Admin\Resources\Settings\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

final class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
