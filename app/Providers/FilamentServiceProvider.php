<?php

declare(strict_types=1);

namespace App\Providers;

use App\Settings\PreferencesSettings;
use Exception;
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
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentTimezone;
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
        $this->configureTimezone();
        $this->configureDateTimeFormats();
        $this->configureTable();
        $this->configureAction();
        $this->configureIcon();
    }

    /**
     * Configure the timezone.
     */
    private function configureTimezone(): void
    {
        try {
            /** @var string $timezone */
            $timezone = resolve(PreferencesSettings::class)->timezone;
            FilamentTimezone::set($timezone);
        } catch (Exception) {
            /** @var string $timezone */
            $timezone = config('app.timezone');
            FilamentTimezone::set($timezone);
        }
    }

    /**
     * Configure the date and time formats.
     */
    private function configureDateTimeFormats(): void
    {
        try {
            $settings = resolve(PreferencesSettings::class);

            // Configure table columns with custom display format only
            Table::configureUsing(function (Table $table) use ($settings): void {
                $dateFormat = $settings->dateFormat;
                $timeFormat = $settings->timeFormat;

                if ($dateFormat && $timeFormat) {
                    $table->defaultDateDisplayFormat($dateFormat)
                        ->defaultTimeDisplayFormat($timeFormat)
                        ->defaultDateTimeDisplayFormat($dateFormat.' '.$timeFormat);
                }
            });

            // Configure infolist components with custom display format only
            Schema::configureUsing(function (Schema $schema) use ($settings): void {
                $dateFormat = $settings->dateFormat;
                $timeFormat = $settings->timeFormat;

                if ($dateFormat && $timeFormat) {
                    $schema->defaultDateDisplayFormat($dateFormat)
                        ->defaultTimeDisplayFormat($timeFormat)
                        ->defaultDateTimeDisplayFormat($dateFormat.' '.$timeFormat);
                }
            });

            // Configure DateTimePicker, DatePicker and TimePicker with custom display format only
            DateTimePicker::configureUsing(function (DateTimePicker $component) use ($settings): void {
                $dateFormat = $settings->dateFormat;
                $timeFormat = $settings->timeFormat;

                if ($dateFormat && $timeFormat) {
                    $component->defaultDateDisplayFormat($dateFormat)
                        ->defaultTimeDisplayFormat($timeFormat)
                        ->defaultDateTimeDisplayFormat($dateFormat.' '.$timeFormat);
                }
            });
        } catch (Exception) {
            // Silently fail if settings are not available
            // Components will use their default formats
        }
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
