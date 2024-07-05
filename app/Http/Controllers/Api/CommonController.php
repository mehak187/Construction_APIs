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
use App\Models\Leave;
use App\Models\attendance;
use App\Models\SiteManagement;
use App\Models\Salary;
use Validator;
use Mail;
use Illuminate\Support\Str;


class CommonController extends Controller
{
    use ApiResponseTrait;
       public function myprofile(){
        try {
            $user_id=auth()->user()->id;
            $profile = User::where('id',$user_id)->first();
            $success = 'Profile';
            return $this->sendJsonResponse($success, $profile);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
}
