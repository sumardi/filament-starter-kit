<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;

abstract class TestCase extends BaseTestCase
{
    public User $user;

    protected $seed = true;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    final public function asUser(): self
    {
        $this->user = User::factory()->create()
            ->assignRole('admin');
        $this->actingAs($this->user);

        return $this;
    }
}
