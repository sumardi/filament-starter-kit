<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;

abstract class TestCase extends BaseTestCase
{
    public User $user;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
    }

    final public function asUser(): self
    {
        $this->actingAs($this->user = User::factory()->create());

        return $this;
    }
}
