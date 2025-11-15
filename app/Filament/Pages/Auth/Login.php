<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Override;
use Filament\Auth\Pages\Login as BasePage;

final class Login extends BasePage
{
    #[Override]
    public function mount(): void
    {
        parent::mount();

        if (app()->isLocal()) {
            $this->form->fill([
                'email' => config('app.default_user.email'),
                'password' => 'password',
                'remember' => true,
            ]);
        }
    }
}
