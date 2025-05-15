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

    //  Need adjustment on apiUpdate: hasErrors->updateNotWorking->notAuthenticated & CSRF token mismatch
    public function apiUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'username' => 'required',
            'password' => 'nullable',
        ]);

        $user = $this->repository->getUserById($validated['id']); 

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = md5($validated['password']); 
        }

        $this->repository->updateUser($user, $updateData);

        return response()->json([
            'success' => true,
            'message' => 'Information updated successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
            ]
        ]);
    }

    public function apiDeleteUser(Request $request)
    {
         $request->validate([
            'username' => 'required',
            'client_username' => 'required',
        ]);

        $username = $request->input('username');
        $clientUsername = $request->input('client_username');

        if ($username !== $clientUsername) {
            return response()->json(['message' => 'Unauthorized: username mismatch'], 401);
        }

        $user = $this->repository->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $user->delete();

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
