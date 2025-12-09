<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\ActivityLogs\Pages;

use App\Filament\Admin\Resources\Settings\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;
use Override;

final class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    /**
     * @return array<mixed>
     */
    #[Override]
    public function getBreadcrumbs(): array
    {
        return [
            self::$resource::getUrl('index') => self::$resource::getBreadcrumb(), // @phpstan-ignore array.invalidKey
            $this->getBreadcrumb(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
