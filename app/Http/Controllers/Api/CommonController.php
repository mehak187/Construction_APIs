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
    public function empProfile(){
        // --------for office worker------
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office workers can access this.'], 403);
            }
            $profile = User::where('role','supervisor') ->orWhere('role', 'siteWorker')
            ->orderBy('id', 'desc')->get();
            $success = 'Profile';
            return $this->sendJsonResponse($success, $profile);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function todayAttendance(Request $request){
        try {
            $userId = auth()->user()->id;
            $date = $request->input('date');
            $attendanceRecords = Attendance::whereDate('checkin', $date)->where('uid', $userId)->first();
            
            return response()->json(['success' => true, 'data' => $attendanceRecords], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error.', 'message' => $e->getMessage()], 500);    
        }
    }
    
}
