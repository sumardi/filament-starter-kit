<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Settings;

use Override;
use App\Helpers\LocaleFinder;
use App\Settings\PreferencesSettings;
use BackedEnum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

final class Preferences extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ComputerDesktop;

    protected static string $settings = PreferencesSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $slug = 'settings/preferences';

    #[Override]
    public function form(Schema $schema): Schema
    {
        $timezoneOptions = collect(timezone_identifiers_list())
            ->mapWithKeys(fn (string $timezone): array => [$timezone => $timezone]);

        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Preferences'))
                    ->description(__('Configure locale, datetime and timezone preferences for the application.'))
                    ->inlineLabel()
                    ->components([
                        Select::make('locale')
                            ->options(LocaleFinder::getAvailableLocales())
                            ->required()
                            ->searchable(),
                        Select::make('timezone')
                            ->options($timezoneOptions)
                            ->required()
                            ->searchable(),
                        Radio::make('dateFormat')
                            ->options([
                                'Y-m-d' => '2017-04-17',
                                'd-m-Y' => '17-04-2017',
                                'm-d-Y' => '04-17-2017',
                            ])
                            ->required(),
                        Radio::make('timeFormat')
                            ->options([
                                'H:i:s' => '23:45:00',
                                'h:i:s A' => '11:45:00 PM',
                                'h:i A' => '11:45 PM',
                                'H:i' => '23:45',
                            ])
                            ->required(),
                    ]),
            ]);
    }
}
