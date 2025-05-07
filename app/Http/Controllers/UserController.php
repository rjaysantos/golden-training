<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required', 'max:50'],
            'username' => ['required', 'min:4', 'max:12', 'unique:users'],
            'password' => ['required']
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        User::create($incomingFields);

        return redirect('/login')->with('user_registered', 'Register Successful.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        return redirect('/login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard')->with('user_loggedin', 'Login Success.');
        } else {
            return redirect('/login');
        }


        return back()->withErrors([
            'username' => 'Invalid username or password.',
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'max:50'],
            'username' => ['required', 'min:4', 'max:12', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'min:6']
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        /** @var \App\Models\User $user **/
        $user->save();

        return back()->with('updateSuccess', 'Information updated!');
    }

    public function deleteUser(Request $request)
    {
        $user = auth()->user();
        auth()->logout();

        /** @var \App\Models\User $user **/
        // Delete the user
        $user->delete();

        $request->session()->invalidate();

        // Redirect to register
        return redirect('/')->with('accountDeleted', 'Your account has been deleted.');
    }
}
