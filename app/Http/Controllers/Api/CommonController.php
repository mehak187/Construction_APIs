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
            ->get();
            $success = 'Profile';
            return $this->sendJsonResponse($success, $profile);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
   public function todayAttendance(Request $request){
        try {
            $date = $request->input('date');
            
            // Assuming 'attendance' is your model and 'checkin' is a date/datetime field
            $attendanceRecords = Attendance::whereDate('checkin', $date)->first();
            
            return response()->json(['success' => true, 'data' => $attendanceRecords], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error.', 'message' => $e->getMessage()], 500);    
        }
    }
    public function mysalary(Request $request){ 
        try {
            $validatedData = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
                'userId' => 'required',
                'pay' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            // $user = Auth::user();
            // if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
            //     return response()->json(['error' => 'Only site workers and supervisers can access this.'], 403);
            // }
            $liveURL = "http://constructionapp.wantar-system.com/uploads/";
            $checkinPhoto_name = $liveURL . time() . "_" . $checkinPhoto->getClientOriginalName();
            $destinationpath = public_path('uploads/');
            $checkinPhoto->move($destinationpath,$checkinPhoto_name);

            $input = $request->all();
            $input['uid'] = $userId;
            $input['status'] = "incomplete";
            $input['checkinPhoto'] = $checkinPhoto_name;
            $input['checkout'] = "";
            $input['checkoutPhoto'] = "";
            if(auth()->user()->role=="supervisor"){
                $input['superviserid'] = 0;
            }
            $user = attendance::create($input);
            return response()->json(['msg' => 'You are checked in successfully.'], 201);
        } 
            catch (\Exception $e) {
                return $this->sendError('Error.', $e->getMessage());    
            }
    }
    
}
