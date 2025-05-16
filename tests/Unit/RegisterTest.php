<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_with_valid_data(): void
    {
        //**@test */

        // test user
        $userData = [
            'name' => 'test',
            'username' => 'test',
            'password' => 'testpass',
        ];

        $response = $this->postJson('/apiRegister', $userData);

        $response -> assertStatus(201)
        ->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'username',
                'created_at',
                'updated_at',
            ]
        ]) -> assertJson([
            'message' => 'User Registered Successfully'
        ]);

        //check if user was actually saved in the database
        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'username' => 'test',
        ]);

        //check if the password stored was hashed
            $user = User::where('username', 'test') ->first();
            $this->assertNotEquals('testpass', $user->password);
    }

    /** @test */
    public function test_registration_fails_with_duplicate_username() {
        
        User::create([
            'name' => 'test',
            'username' => 'test',
            'password' => 'testpass',
        ]);

        $duplicateData = [
            'name' => 'test1',
            'username' => 'test', //
            'password' => 'test1pass'
        ];

        //register with duplicate username
        $response = $this->postJson('/apiRegister', $duplicateData);

        //checking if validation fails properly
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['username']);

        //check that only one user exist in the database
        $this->assertDatabaseCount('users', 1);
    }
}
