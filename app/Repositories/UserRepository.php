<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function getUserByUsername(string $username): ?User
    {
        return User::where('username', $username)->first();
    }

    public function validatedCredentials(string $username, string $password): bool
    {
        $user = User::where('username', $username)->first();
        
        // Check if user exists and password matches
        return $user && Hash::check($password, $user->password);
    }

    public function loginUser($user)
    {
        Auth::login($user);
    }

    public function deleteCurrentUser()
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        $deleted = $user->delete();

        // Logout and invalidate session
        if ($deleted) 
        {
            Auth::logout();
            request()->session()->invalidate();
        }
        return $deleted;
    }

    public function updateUser(array $data): ?User
    {
        $user = Auth::user();
        
        $user->name = $data['name'];
        $user->username = $data['username'];
        
        if (isset($data['password']) && $data['password']) {
            $user->password = bcrypt($data['password']);
        }
        
        return $user->save() ? $user : null;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return response()->json(null, 204);
    }
}