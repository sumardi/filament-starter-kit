<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Table;
use Filament\View\PanelsIconAlias;
use Illuminate\Support\ServiceProvider;
use Override;

final class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureTable();
        $this->configureAction();
        $this->configureIcon();
    }

    /**
     * Configure the table.
     */
    private function configureTable(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table->striped()
                ->deferLoading();
        });
    }

    /**
     * Configure the action.
     */
    private function configureAction(): void
    {
        Action::configureUsing(function (Action $action): void {
            $action->modalWidth('lg');
        });
        EditAction::configureUsing(function (EditAction $action): void {
            $action->icon('heroicon-m-pencil-square');
        });
        CreateAction::configureUsing(function (CreateAction $action): void {
            $action->icon('heroicon-m-plus');
        });
        DeleteAction::configureUsing(function (DeleteAction $action): void {
            $action->icon('heroicon-m-trash');
        });
        ViewAction::configureUsing(function (ViewAction $action): void {
            $action->icon('heroicon-m-eye');
        });
        AttachAction::configureUsing(function (AttachAction $action): void {
            $action->icon('heroicon-m-link');
        });
        DetachAction::configureUsing(function (DetachAction $action): void {
            $action->icon('heroicon-m-link-slash');
        });
        RestoreAction::configureUsing(function (RestoreAction $action): void {
            $action->icon('heroicon-m-arrow-path');
        });
        ForceDeleteAction::configureUsing(function (ForceDeleteAction $action): void {
            $action->icon('heroicon-m-archive-box-x-mark');
        });
        ImportAction::configureUsing(function (ImportAction $action): void {
            $action->icon('heroicon-m-arrow-down-tray');
        });
        ExportAction::configureUsing(function (ExportAction $action): void {
            $action->icon('heroicon-m-arrow-up-tray');
        });
    }

    /**
     * Configure the icon.
     */
    private function configureIcon(): void
    {
        FilamentIcon::register([
            'panels::pages.dashboard.navigation-item' => 'heroicon-m-squares-2x2',
        ]);
        FilamentIcon::register([
            PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => 'lucide-sidebar-open',
        ]);
        FilamentIcon::register([
            PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => 'lucide-sidebar-close',
        ]);
        FilamentIcon::register([
            PanelsIconAlias::USER_MENU_PROFILE_ITEM => 'lucide-user-circle',
        ]);
        FilamentIcon::register([
            PanelsIconAlias::USER_MENU_LOGOUT_BUTTON => 'lucide-log-out',
        ]);
        FilamentIcon::register([
            PanelsIconAlias::WIDGETS_ACCOUNT_LOGOUT_BUTTON => 'lucide-log-out',
        ]);
    }
}
