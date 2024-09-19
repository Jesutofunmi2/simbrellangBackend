<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_successful()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create([
            'password' => Hash::make('Balo5544'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'Balo5544'
        ];

        $response = $this->postJson(route('auth.login'), $data);
        $response->assertStatus(200)
             ->assertJsonStructure([
            'message',
            'access_token',
            'token_type',
            'data' => [
                'id',
                'name',
                'email',
                'project',
            ],
        ]);
    }
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create([
            'password' => Hash::make('Balo5544'),
        ]);
        $data = [
            'email' => $user->email,
            'password' => 'Balo5566'
        ];

        $response = $this->postJson( route('auth.login'), $data);
        $response->assertStatus(422);
    }
}
