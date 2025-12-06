<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Patient;
use App\Models\ClinicDBInfo;
use Illuminate\Http\Request;
use App\Mail\RegistrationMail;
use App\Mail\ResetPasswordMail;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Helper
{
    /**
     * Helper for Upload Image
     */
    public static function uploadImage($request,$field)
    {
        $imagePath = null;
        if ($request->hasFile($field)) {
            $image = $request->file($field);
            $imagePath = $image->store($field, 'public');
        }
        return $imagePath;
    }

    /**
     * Helper for Update Image
     */
    public static function updateImage($request,$user,$field)
    {
        $imagePath = null;
        if ($request->hasFile($field)) {
            $oldImage = $user->getRawOriginal($field);
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            $image = $request->file($field);
            $imagePath = $image->store($field, 'public');
        }
        return $imagePath;
    }

    /**
     * Helper for Delete Image
     */
    public static function deleteImage($user,$field)
    {
        $oldImage = $user->getRawOriginal($field);
        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
            Storage::disk('public')->delete($oldImage);
        }
    }

    /**
     * Helper for success response
     */
    public static function successResponse($message, $data = null, $code = 200)
    {
        $response = ['status' => 'success', 'message' => $message];
        if (!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, $code);
    }

    /**
     * Helper for error response
     */
    public static function errorResponse($message, $code)
    {
        return response()->json(['status' => 'error', 'message' => $message], $code);
    }

    /**
     * Helper for validation error response
     */
    public static function checkValidation($validator)
    {
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    }
}
