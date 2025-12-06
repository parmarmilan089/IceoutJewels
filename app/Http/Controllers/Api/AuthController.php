<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Routing\Controllers\Middleware;


class AuthController extends Controller
{


    const APPROVED_STATUS = 1;
    const PASSWORD_LENGTH = 10;
    const MAX_IMAGE_SIZE = 8192;

    // Select fields for queries
    const SELECT_FIELDS = [
        'id', 'first_name','last_name', 'email', 'phone', 'profile',
        'is_admin', 'status', 'created_at'
    ];

    /**
     * Validate request data
     */
    private function validateRequest(Request $request, ?string $id = null): \Illuminate\Contracts\Validation\Validator
    {

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email'],
            'phone' => ['required','string'],
            'password' => ['required', 'string','min:8'],
        ];

        return Validator::make($request->all(), $rules);
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login', 'register','googleAuth']),
        ];
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validateRequest($request);
        if ($error = Helper::checkValidation($validator)) {
            return $error;
        }

        try {

            $existingUser = User::whereEmail($request->email)->user()->approved()->first();

            if ($existingUser) {

                if (!Hash::check($request->password, $existingUser->password)) {
                    return Helper::errorResponse('This email is already registered with a different password. Please use the correct password or try password reset.', 401);
                }

                // Login existing user
                $token = JWTAuth::fromUser($existingUser);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Welcome back! Logged in successfully.',
                    'user' => $existingUser,
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                    'action' => 'login'
                ]);

            }
            $data = $request->all();
            $data['password']= Hash::make($request->password);
            // Create user
            $user = User::create($data);
            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Return success response with token
            return response()->json([
                'status' => 'success',
                'message' => 'User successfully registered',
                'user' => $user->fresh(),
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60 // Convert minutes to seconds
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return Helper::errorResponse('Registration failed !!.',500);
        }
    }

    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($error = Helper::checkValidation($validator)) {
            return $error;
        }

        try {
            $credentials = $request->only('email', 'password');
            $record = User::whereEmail($request->email)->user()->approved()->first();

            if (!$record) {
                return Helper::errorResponse('User not found or is inactive.',404);
            }

            if (!$token = JWTAuth::attempt($credentials)) {

                return Helper::errorResponse('That email or password isn’t right. Please check and try again.',404);
            }

            return $this->createNewToken($token);

        } catch (JWTException $e) {
            Log::error('Login error: ' . $e->getMessage());
            return Helper::errorResponse('Login Failed !!',500);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return Helper::successResponse('User successfully logged out');
        } catch (JWTException $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return Helper::errorResponse('Could not process logout !!',500);
        }
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();

            return $this->createNewToken($token);
        } catch (JWTException $e) {
            Log::error('Token refresh error: ' . $e->getMessage());
            return Helper::errorResponse('Could not refresh token',500);

        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user || !$user->status) {
                return Helper::errorResponse('User is inactive or does not exist.',404);
            }
            return Helper::successResponse('Profile Fetch successfully', $user);

        } catch (JWTException $e) {
            Log::error('Profile fetch error: ' . $e->getMessage());
            return Helper::errorResponse('Could not fetch user profile',500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'logged in successfully',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => JWTAuth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        try{
            $user = auth()->user();
            $data = $request->all();

            $imagePath = Helper::updateImage($request,$user,'profile');
            if($imagePath){
                $data['profile'] = $imagePath;
            }
            $user->update($data);
            return Helper::successResponse('Profile updated successfully', $user->fresh());

        } catch (\Exception $e) {
            return Helper::errorResponse('Failed to update profile !!',500);
        }
    }


    public function deleteProfile(Request $request)
    {
        try{
            $user = auth()->user();
            Helper::deleteImage($user,'profile');
            $user->delete();

            JWTAuth::invalidate(JWTAuth::getToken());
            return Helper::successResponse('Account deleted successfully');
        } catch (\Exception $e) {
            return Helper::errorResponse('Failed to delete profile !!',500);
        }
    }

    /**
     * Change user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:8|different:current_password',
                'confirm_password' => 'required|same:new_password'
            ]);

            if ($error = Helper::checkValidation($validator)) {
                return $error;
            }

            // Get authenticated user
            $user = auth()->user();
            if (!$user) {
                return Helper::errorResponse('Unauthorized.',401);
            }

            // Check if current password matches
            if (!Hash::check($request->current_password, $user->password)) {
                return Helper::errorResponse('Current password is incorrect.',400);
            }
            $hashedPassword = Hash::make($request->new_password);
            $user->update(['password' => $hashedPassword]);
            return Helper::successResponse('Password changed successfully', $user->fresh());

        } catch (\Exception $e) {
            return Helper::errorResponse('Failed to Change Password !!',500);
        }
    }
}