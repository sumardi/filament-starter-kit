<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Users;

use App\Filament\Admin\Resources\Settings\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Settings\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Settings\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Settings\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Settings\Users\Schemas\UserForm;
use App\Filament\Admin\Resources\Settings\Users\Schemas\UserInfolist;
use App\Filament\Admin\Resources\Settings\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
