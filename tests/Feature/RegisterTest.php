<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_the_registration_form(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Créer un compte');
    }

    public function test_registers_a_user_with_valid_data(): void
    {
        $this->post(route('register'), $this->validPayload())
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
        $this->assertNotNull(session('auth.fingerprint'));
    }

    public function test_rejects_a_duplicated_email(): void
    {
        $this->post(route('register'), $this->validPayload());
        $this->flushSession();

        $this->post(route('register'), $this->validPayload('Alice 2'))
            ->assertSessionHasErrors('email');
    }

    public function test_rejects_a_weak_password(): void
    {
        $this->post(route('register'), $this->validPayload(password: 'weak'))
            ->assertSessionHasErrors('password');
    }

    public function test_blocks_after_five_attempts_per_minute(): void
    {
        foreach (range(1, 5) as $index) {
            $this->post(route('register'), $this->validPayload("User {$index}", "user{$index}@example.com"));
            $this->flushSession();
        }

        $this->post(route('register'), $this->validPayload('User 6', 'user6@example.com'))
            ->assertStatus(429);
    }

    /**
     * @return array{name: string, email: string, password: string, password_confirmation: string}
     */
    private function validPayload(
        string $name = 'Alice Dupont',
        string $email = 'alice@example.com',
        string $password = 'Str0ng!Pass#2026',
    ): array {
        return [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }
}
