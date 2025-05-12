<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // test user
        $this->user = User::create([
            'name' => 'test',
            'username' => 'test',
            'password' => Hash::make('testpass'),
        ]);
    }

    /** @test */
    public function test_user_can_login_with_valid_credentials()
    {
        $loginData = [
            'username' => 'test',
            'password' => 'testpass'
        ];

        $response = $this->postJson('/apiLogin', $loginData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'username',
                    'created_at',
                    'updated_at'
                ]
            ]);
        
        // Check that the user is authenticated
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $loginData = [
            'username' => 'test',
            'password' => 'wrongpassword'
        ];

        $response = $this->postJson('/apiLogin', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
        
        // Check that the user is not authenticated
        $this->assertGuest();
    }

    /** @test */
    public function test_login_requires_username()
    {
        $loginData = [
            'password' => 'testpass'
        ];

        $response = $this->postJson('/apiLogin', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
        
        $this->assertGuest();
    }

    /** @test */
    public function test_login_requires_password()
    {
        $loginData = [
            'username' => 'test'
        ];

        $response = $this->postJson('/apiLogin', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
        
        $this->assertGuest();
    }

    /** @test */
    public function test_nonexistent_user_cannot_login()
    {
        $loginData = [
            'username' => 'nonexistentuser',
            'password' => 'testpass'
        ];

        $response = $this->postJson('/apiLogin', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
        
        $this->assertGuest();
    }
}