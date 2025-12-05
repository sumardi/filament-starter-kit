<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\File;

final class LocaleFinder
{
    /**
     * Get an array of available locales.
     *
     * @return array<string>
     */
    public static function getAvailableLocales(): array
    {
        $locales = [];
        $langPath = resource_path('lang');

        if (File::exists($langPath)) {
            $jsonFiles = File::files($langPath);

            foreach ($jsonFiles as $file) {
                $locale = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $locales[$locale] = $locale;
            }
        }

        return $locales;
    }
}
