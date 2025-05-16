<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $anotherUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // test users
        $this->user = User::create([
            'name' => 'test',
            'username' => 'test',
            'password' => Hash::make('testpass'),
        ]);

        $this->anotherUser = User::create([
            'name' => 'another',
            'username' => 'another',
            'password' => Hash::make('testpass'),
        ]);
    }

    /** @test */
    public function test_user_can_update_profile_information()
    {
        // Act as authenticated user
        $this->actingAs($this->user);
        
        $updateData = [
            'name' => 'updated',
            'username' => 'updated',
        ];

        $response = $this->patchJson('/apiUpdate', $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Information updated successfully!',
                'user' => [
                    'id' => $this->user->id,
                    'name' => 'updated',
                    'username' => 'updated',
                ]
            ]);

        // Verify database was updated
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'updated',
            'username' => 'updated',
        ]);
    }

    /** @test */
    public function test_user_can_update_password()
    {
        // Act as authenticated user
        $this->actingAs($this->user);
        
        $newPassword = 'newpass';
        $updateData = [
            'name' => 'test',
            'username' => 'test',
            'password' => $newPassword,
        ];

        $response = $this->patchJson('/apiUpdate', $updateData);

        $response->assertStatus(200);

        // Fetch fresh user and verify password was updated
        $updatedUser = User::find($this->user->id);
        $this->assertTrue(Hash::check($newPassword, $updatedUser->password));
    }

    /** @test */
    public function test_user_cannot_update_profile_without_name()
    {
        // Act as authenticated user
        $this->actingAs($this->user);
        
        $updateData = [
            'username' => 'updated',
        ];

        $response = $this->patchJson('/apiUpdate', $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
        
        // Verify database was not updated
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'test',
            'username' => 'test',
        ]);
    }

    /** @test */
    public function test_user_cannot_update_profile_without_username()
    {
        // Act as authenticated user
        $this->actingAs($this->user);
        
        $updateData = [
            'name' => 'updated',
        ];

        $response = $this->patchJson('/apiUpdate', $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
        
        // Verify database was not updated
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'test',
            'username' => 'test',
        ]);
    }

    /** @test */
    public function test_user_cannot_use_existing_username()
    {
        // Act as authenticated user
        $this->actingAs($this->user);
        
        $updateData = [
            'name' => 'updated',
            'username' => 'another',
        ];

        $response = $this->patchJson('/apiUpdate', $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
        
        // Verify database was not updated
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'test',
            'username' => 'test',
        ]);
    }

    /** @test */
    public function test_user_can_keep_same_username()
    {
        // Act as authenticated user
        $this->actingAs($this->user);
        
        $updateData = [
            'name' => 'updated',
            'username' => 'test',
        ];

        $response = $this->patchJson('/apiUpdate', $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Information updated successfully!',
                'user' => [
                    'id' => $this->user->id,
                    'name' => 'updated',
                    'username' => 'test',
                ]
            ]);

        // Verify database was updated (only name changed)
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'updated',
            'username' => 'test',
        ]);
    }
}