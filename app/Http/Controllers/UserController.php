<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function apiRegister(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        if (User::where('username', $fields['username'])->exists()) {
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

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        auth()->login($user);
        return response()->json(['message' => 'Login successful', 'user' => $user]);
    }

    public function apiUpdate(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];

        if (!empty($request->password)) 
            $user->password = bcrypt($request->password);
            $user->save();

        return response()->json(['message' => 'Information updated successfully!', 
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
            ]
        ]);
    }

    public function apiDeleteUser(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Your account has been deleted successfully.',
            ]);
        }
    }

    public function apiLogout(Request $request)
    {
        $request->session()->invalidate();
        return response()->json(['message' => 'Logged out']);
    }
}
