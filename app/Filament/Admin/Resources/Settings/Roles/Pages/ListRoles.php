<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Roles\Pages;

use Override;
use App\Filament\Admin\Resources\Settings\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
