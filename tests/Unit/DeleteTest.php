<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        //test user
        $this->user = User::create([
            'name' => 'test',
            'username' => 'test',
            'password' => bcrypt('testpass'),
        ]);
    }

    /** @test */
    public function test_authenticated_user_can_delete_their_account()
    {
        $this->actingAs($this->user);
        
        $response = $this->deleteJson('/apiDeleteUser');

        $response->assertOk()
            ->assertJson([
                'message' => 'Your account has been deleted.'
            ]);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function test_unauthenticated_user_cannot_delete_account()
    {
        $response = $this->deleteJson('/apiDeleteUser');

        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthorized'
            ]);

        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
    }

    /** @test */
    public function test_user_is_logged_out_after_deletion()
    {
        $this->actingAs($this->user);
        $this->assertTrue(Auth::check());
        
        $this->deleteJson('/apiDeleteUser');
        
        $this->assertFalse(Auth::check());
    }
}