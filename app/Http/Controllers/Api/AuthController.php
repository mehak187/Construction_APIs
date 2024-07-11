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
    
            $email = $request->input('email');
            $user = \App\Models\User::where('email', $email)
                                   ->orWhere('phone', $email)
                                   ->orWhere('staff_id', $email)
                                   ->first();
                                //    echo $user->email; die();
                                if($user->email!=NULL){
                                    $uemail = $user->email;
                                }
                                if($user->phone!=NULL){
                                    $uemail = $user->phone;
                                }
                                if($user->staff_id!=NULL){
                                    $uemail = $user->staff_id;
                                }
            if ($user && $user->status == 'active' && Auth::attempt(['email' => $uemail, 'password' => $request->password])
            || Auth::attempt(['phone' => $uemail, 'password' => $request->password])
            || Auth::attempt(['staff_id' => $uemail, 'password' => $request->password])) {
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