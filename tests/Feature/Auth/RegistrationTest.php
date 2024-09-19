<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $this->withoutMiddleware();

        $response = $this->postJson(route('auth.register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

           $response->assertStatus(201);
    }

    public function test_user_cannot_register_with_invalid_credentials()
    {

        $data = [
            'name' => 'Balogun Joseph',
            'email' => 'email',
            'password' => 'Balo5566',
            'password_confirmation' => 'Balo5566',
        ];

        $response = $this->postJson( route('auth.register'), $data);
        $response->assertStatus(422);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        
        $user = User::factory()->create();
        $data = [
            'name' => 'Balogun Joseph',
            'email' => $user->email,
            'password' => 'Balo5566',
            'password_confirmation' => 'Balo5566',
        ];

        $response = $this->postJson( route('auth.register'), $data);
        $response->assertStatus(422);
    }
}
