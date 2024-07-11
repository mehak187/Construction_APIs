<?php

namespace App\Http\Controllers\Api;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    use ApiResponseTrait;
    public function login(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->sendAuthError('Validation Error.', $validator->errors());
            }
    
            $credentials = $request->only('password');
            $identifier = $request->input('email');
            $user = \App\Models\User::where('email', $identifier)
                ->orWhere('phone', $identifier)
                ->orWhere('staff_id', $identifier)
                ->first();
    
            if ($user && $user->status == 'active' && Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
                $user = Auth::user();
                $data['token'] = $user->createToken('MyApp')->plainTextToken;
                $data['id'] = $user->id;
                $data['role'] = $user->role;
                $data['status'] = $user->status;
                return $this->sendJsonResponse('User login successfully.', $data);
            } else {
                if ($user && $user->status != 'active') {
                    return $this->sendAuthError('Account inactive.', ['error' => 'Your account is inactive.']);
                }
                return $this->sendAuthError('Invalid credentials.', ['error' => 'The provided email, phone, or staff ID, or password is incorrect.']);
            }
        } catch (\Exception $e) {
            return $this->sendAuthError('Error.', $e->getMessage());
        }
    }
    
    // public function sendResetLinkEmail(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //                 ? response()->json(['message' => 'Reset link sent to your email.'])
    //                 : response()->json(['error' => 'Unable to send reset link.'], 500);
    // }
}