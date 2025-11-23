<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Roles;

use App\Filament\Admin\Resources\Settings\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Settings\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Settings\Roles\Pages\ListRoles;
use App\Filament\Admin\Resources\Settings\Roles\Pages\ViewRole;
use App\Filament\Admin\Resources\Settings\Roles\RelationManagers\UsersRelationManager;
use App\Filament\Admin\Resources\Settings\Roles\Schemas\RoleForm;
use App\Filament\Admin\Resources\Settings\Roles\Schemas\RoleInfolist;
use App\Filament\Admin\Resources\Settings\Roles\Tables\RolesTable;
use App\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    protected static ?string $recordTitleAttribute = 'display_name';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return RoleInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            'users' => UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
