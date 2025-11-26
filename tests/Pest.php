<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->group('Auth')
    ->in('Feature/Filament/Auth');

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->group('Admin')
    ->beforeEach(function (): void {
        filament()->setCurrentPanel('admin');
        $this->asUser();
    })
    ->in('Feature/Filament/Admin');

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->group('User')
    ->beforeEach(function (): void {
        filament()->setCurrentPanel('user');
        $this->asUser();
    })
    ->in('Feature/Filament/User');
