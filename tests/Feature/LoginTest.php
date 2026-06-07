<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_the_login_form(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Connexion');
    }

    public function test_logs_in_a_user_with_valid_credentials(): void
    {
        $this->post(route('register'), $this->registrationPayload());
        $this->flushSession();

        $this->post(route('login'), [
            'email' => 'alice@example.com',
            'password' => 'Str0ng!Pass#2026',
        ])->assertRedirect(route('dashboard'));

        $this->assertNotNull(session('auth.fingerprint'));
    }

    public function test_rejects_invalid_credentials(): void
    {
        $this->post(route('login'), [
            'email' => 'nobody@example.com',
            'password' => 'WrongPass!123',
        ])->assertSessionHasErrors('credentials');
    }

    public function test_protects_the_dashboard_from_guests(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    /**
     * @return array{name: string, email: string, password: string, password_confirmation: string}
     */
    private function registrationPayload(): array
    {
        return [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'Str0ng!Pass#2026',
            'password_confirmation' => 'Str0ng!Pass#2026',
        ];
    }
}
