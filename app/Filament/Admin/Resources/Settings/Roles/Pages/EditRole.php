<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Roles\Pages;

use App\Filament\Admin\Resources\Settings\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
