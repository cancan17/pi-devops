<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request) 
    {
        // validate filds
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Create user
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password']),
        ]);

        // Return user and token in response
        return response([
            'user ' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ]);
    }

    // Login user
    public function login(Request $request) 
    {
        //validate filds
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Attempt login
        if(!Auth::attempt($attrs))
        {
            return response([
                'message' => 'Invalid credentials.'
            ], 403);
        }

        // Return user and token in response
        return response([
            'user ' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }

    // Logout
    public function logout() 
    {
        auth()->user()->tokens()->delete();
        return response ([
            'message' =>'Logout sucess'
        ], 200);
    }

    // Get user data
    public function user() 
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }
}
