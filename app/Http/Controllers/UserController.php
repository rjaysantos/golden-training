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

        $user = $this->repository->createUser($request->name, $request->username, $request->password);

        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
            ]
        ], 201);
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        $user = $this->repository->getUserByUsernamePassword($request->username, $request->password);

        if (is_null($user) === true) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(
            [
                'message' => 'Login successful', 
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                ],
            ]);
    }

    public function apiUpdate(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'name' => 'required',
            'password' => 'nullable',
        ]);

        $user = $this->repository->getUserByUsername($validated['username']);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $updateUserData = [
            'name' => $validated['name'],
        ];

        if (!empty($validated['password'])) {
            $updateUserData['password'] = md5($validated['password']);
        }

        $updated = $this->repository->updateUser($user, $updateUserData);

        if (!$updated) {
            return response()->json(['success' => false, 'message' => 'Update failed'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Information updated successfully!',
            'user' => [
                'name' => $updateUserData['name'],
                'username' => $validated['username'],
            ]
        ]);
    }

    public function apiDeleteUser(Request $request)
    {
         $request->validate([
            'username' => 'required',
        ]);

        $username = $request->input('username');

        $user = $this->repository->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $this->repository->deleteUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Profile deleted successfully.',
        ]);
    }

    public function apiLogout(Request $request)
    {
        return response()->json(['message' => 'Logged out successfully']);
    }

}
