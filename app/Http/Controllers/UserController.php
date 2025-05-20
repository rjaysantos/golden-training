<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;

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

        if ($user) {
            $api_token = Str::random(64);

            $user->api_token = $api_token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'api_token' => $api_token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function apiGetAuthenticatedUser(Request $request) 
    {
        $api_token = $request->bearerToken();
        $user = $this->repository->authenticateToken($api_token);

        if(!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json ([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
        ]);
    }

    public function apiUpdate(Request $request)
    {
        $api_token = $request->bearerToken();
        $user = $this->repository->authenticateToken($api_token);

        $request->validate([
            'name' => 'sometimes',
            'username' => 'sometimes',
            'password' => 'nullable',
        ]);

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validateData = $request->validate([
            'name' => 'sometimes',
            'username' => 'sometimes|unique:users,username,' . $user->id,
            'password' => 'nullable',
        ]);

        $updateData = [];

        if ($request->has('name')) {
            $updateData['name'] = $validateData['name'];
        }

        if ($request->has('username')) {
            $updateData['username'] = $validateData['username'];
        }

        if ($request->filled('password')) {
            $updateData['password'] = md5($validateData['password']);
        }

        if (!empty($updateData)) {
            $this->repository->updateUser($user, $updateData);
            $user->refresh();
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
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
        $api_token = $request->bearerToken();
        $user = $this->repository->authenticateToken($api_token);

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['message' => 'You have been logged out.']);
    }

}
