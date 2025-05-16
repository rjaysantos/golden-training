<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function updateUser(User $user, User $id, array $data): bool
    {
        return User::where('id', $id)->update($user);
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}