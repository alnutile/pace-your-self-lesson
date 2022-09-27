<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->agrees_to_terms);
        $this->assertInstanceOf(Carbon::class, $user->agrees_to_terms);
    }
}
