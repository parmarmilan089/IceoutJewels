<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ForgotPasswordController extends Controller
{

    /**
     * Show the form for creating a new resource for forgot password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($error = Helper::checkValidation($validator)) {
            return $error;
        }

        try {
            // Retrieve the model instance based on the email
            $record = User::whereEmail($request->email)->user()->approved()->first();

            if (!$record) {
                return Helper::errorResponse('User not found or is inactive.',404);
            }

            $token = Str::random(60);

            // Update or insert the password reset token
            DB::table('password_reset_tokens')->updateOrInsert([
                'email' => $record->email
            ], [
                'email' => $record->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]);

            // Prepare mail data
            $maildata = [
                'first_name' => $record->first_name,
                'last_name' => $record->last_name,
                'email' => $record->email,
                'token' => $token,
                'reset_url' => $request->reset_url,
            ];

            // Send the password reset email
            // Mail::to($record->email)->send(new ResetPasswordMail($maildata));

            return Helper::successResponse('Reset Password Link successfully send to your Mail.',$token);

        } catch (Exception $e) {
            return $e->getMessage();
            return Helper::errorResponse('Something Went Wrong !!.',500);
        }
    }

    /**
     * Show the form for creating a new resource for reset password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        try{
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'email' => 'required|email',
                'token' => 'required|string',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|same:password'
            ]);

            if ($error = Helper::checkValidation($validator)) {
                return $error;
            }

            $passwordReset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

            if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
                return Helper::errorResponse('Invalid or expired token.',400);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return Helper::successResponse('Password reset successfully.');

        } catch (Exception $e) {
            return Helper::errorResponse('Something Went Wrong !!.',500);
        }
    }


    public function changePassword(Request $request){

        try{

            $validator = $this->validateRequest($request);
            if ($error = Helper::checkValidation($validator)) {
                return $error;
            }

            $user = auth()->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return Helper::errorResponse('The provided old password is incorrect.',400);
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return Helper::successResponse('Password changed successfully.');

        } catch (Exception $e) {
            return Helper::errorResponse('Something Went Wrong !!.',500);
        }
    }

}
