<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class LoginController extends Controller implements HasMiddleware
{

     public static function middleware(): array
    {
        return [
            new Middleware('guest:Admin', except: ['adminLogout']),
        ];
    }

     public function adminLogin()
    {
        if (Auth::guard('Admin')->check()) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('admin.login');
        }
    }

    public function adminLoginCheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        $user = User::admin()->whereEmail($request->input('email'))->first();

        if(!$user) {
            return redirect()->route('admin.login')->with('error', 'Oops! You are not an active user in this system!');
        }
        if (Auth::guard('Admin')->attempt($request->only('email', 'password'), $request->filled('remember_me'))) {
            return redirect()->intended(route('admin.dashboard'))->with('success', "Game On! You're logged in...");
        } else{
            return redirect()->route('admin.login')->with('error', 'Oops! Login failed. Please try again!');
        }

    }

    public function adminLogout(Request $request)
    {
        if(Auth::guard('Admin')->check()) {
            Auth::guard('Admin')->logout();
        }
        return redirect()->route('admin.login')->with('info', "You've been logged out! Come back soon...");
    }

    public function checkUniqueEmail(Request $request)
    {
        // If updating an existing user, exclude it from the unique check
        $id = $request->input('id');
        $email = $request->input('email');

        $user = User::user()->approved()->whereEmail($email);

        // If we are updating a user, exclude the current user's ID from the unique check
        if ($id) {
            $query->where('id', '!=', $id);
        }

        // Check if any other user exists with the same email
        $exists = $query->exists();

        return response()->json(!$exists); // Returns true (valid) or false (invalid)
    }
}
