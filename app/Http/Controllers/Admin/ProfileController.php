<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $data = User::findorfail(Auth::id());
        return view('admin.profile', compact('data'));
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072', // Max size 3MB
        ]);

        $data = $request->all();
        $user = User::find(Auth::id());
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];

        $imagePath = Helper::updateImage($request,$user,'profile');
        if($imagePath){
             $user->profile = $imagePath;
        }
        $result = $user->save();
        if ($result == true) {
            return redirect()->route('admin.profile')
                ->with('success', 'Profile Updated Successfully!');
        } else {
            return redirect()->route('admin.profile')
                ->with('error', 'Something Went Wrong.');
        }
    }

    public function change_password()
    {
        return view('admin.change_password');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[0-9]/', // At least one number
                'regex:/[@$!%*?&]/', // At least one special character
            ],
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::find(Auth::id());
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                return redirect()->route('admin.change_password')->with('success', 'Password Changed Successfully!');
            } else {
                return redirect()->route('admin.change_password')->with('error', 'Something Went Wrong.');
            }
        } else {
            return redirect()->route('admin.change_password')->with('error', 'Please Provide a Valid Old Password');
        }
    }
}
