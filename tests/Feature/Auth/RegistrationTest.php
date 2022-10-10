<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use YlsIdeas\FeatureFlags\Facades\Features;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_ui')
            ->andReturn(false);

        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_validation')
            ->once()
            ->andReturn(false);

        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_model')
            ->once()
            ->andReturn(true);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_terms_validation()
    {
        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_ui')
            ->andReturn(false);

        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_validation')
        ->andReturn(true);

        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_model')
            ->never();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_saves_terms()
    {
        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_ui')
            ->andReturn(false);

        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_validation')
            ->andReturn(false);

        Features::shouldReceive('accessible')
            ->with('agrees_to_terms_model')
            ->andReturn(true);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'agrees_to_terms' => true,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertNotNull(User::first()->agrees_to_terms);
    }
}
