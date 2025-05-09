<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function apiRegister(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
        }

        return response()->json(['message' => 'User registered successfully'], 201); // 201 Created
    }


    public function apiLogout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['message' => 'Login successful', 'user' => $user]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401); //401 indicates unauthorized
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

        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

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
            Auth::logout();

            return response()->json(['message' => 'Your account has been deleted.']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
