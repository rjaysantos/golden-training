<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository //Repository is only for DB queries
{
    public function getUserByUsername(string $username): ?object
    {
        return User::where('username', $username)
            ->first();
    }

    public function getUserByUsernamePassword(string $username, string $password): ?object
    {
        return User::where('username', $username)
            ->where('password', md5($password))
            ->first();
    }

    public function getUserByName(string $name): ?object
    {
        return User::where('name', $name);
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }
    
    public function createUser(string $name, string $username, string $password): ?object
    {
        return User::create([
            'name' => $name,
            'username' => $username,
            'password' => md5($password),
        ]);
    }

    public function authenticateToken (string $api_token) 
    {
        return User::where('api_token', $api_token)
            ->first();
    }

    public function updateUser(User $user, array $data): bool
    {
        return DB::table('users')
            ->where('id', $user->id)
            ->update($data) > 0;
    }

    public function deleteUser(User $user): bool
    {
        return DB::table('users')
            ->where('id', $user->id)
            ->delete() > 0;
    }

    public function saveUserData(User $user): bool
    {
        if ($user->id) {
            return DB::table('users')
                ->where('id', $user->id)
                ->update($user->getAttributes()) > 0;
        }
    }

    public function clearApiToken(User $user): bool
    {
        return DB::table('users')
            ->where('id', $user->id)
            ->update(['api_token' => null]) > 0;
    }

    public function refreshUserById(int $id): ?User
    {
        $data = DB::table('users')->where('id', $id)->first();
        return $data ? new User((array) $data) : null;
    }
}