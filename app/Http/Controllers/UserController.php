<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private UserRepository $repository){}

    public function apiRegister(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $userData = $this->repository->getUserByUsername($fields['username']);

        if ($userData) {
            return response()->json([
                'message' => 'Username already taken'
            ], 409); //conflict status code 
        }

        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);

            return response()->json([
                'message' => 'User Registered Successfully',
                'user' => $user
            ], 201);
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = $this->repository->getUserByUsername($credentials['username']);

        if (!$user || !$this->repository->validatedCredentials($credentials['username'], $credentials['password']))
        {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $this->repository->loginUser($user);

        return response()->json(['message' => 'Login successful', 'user' => $user]);
    }

    public function apiUpdate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'nullable',
        ]);
        
        $updatedUser = $this->repository->updateUser($validated);
        
        if (!$updatedUser) {
            return response()->json(['message' => 'Update failed'], 500);
        }
        
        return response()->json([
            'message' => 'Profile updated',
            'user' => [
                'id' => $updatedUser->id,
                'name' => $updatedUser->name,
                'username' => $updatedUser->username
            ]
        ]);
    }

    public function apiDeleteUser(Request $request)
    {
        $result = $this->repository->deleteCurrentUser();
        
        if ($result === false) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        return response()->json(['message' => 'Account deleted successfully']);
    }

    public function apiLogout(Request $request)
    {
        $request->session()->invalidate();
        return response()->json(['message' => 'Logged out']);
    }

}
