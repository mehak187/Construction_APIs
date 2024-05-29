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
use App\Models\Attendance;
use Validator;
use Mail;
use Illuminate\Support\Str;

class WorkerController extends Controller
{
    use ApiResponseTrait;
    public function test()
    { 
        $userId=auth()->user()->id;
        echo $userId;die();
    }
    public function leave(Request $request){ 
        try {
            $validatedData = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
                'timeAdded' => 'required',
                'attachment' => 'required',
                'leaveType' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office workers can apply for leave.'], 403);
            }
            $userId=auth()->user()->id;
            $photo = $request->file('attachment');
            $photo_name = time() . "_" .$photo->getClientOriginalName();
            $destinationpath = public_path('uploads/');
            $photo->move($destinationpath,$photo_name);
            $input = $request->all();
            $input['userId'] = $userId;
            $input['status'] = "pending";
            $input['attachment'] = $photo_name;
            $user = Leave::create($input);
            return response()->json(['msg' => 'Leave request sent successfully.'], 403);
        } 
            catch (\Exception $e) {
                return $this->sendError('Error.', $e->getMessage());    
            }
    }
    public function myLeaves(){
        try {
            $user_id=auth()->user()->id;
            $products = Leave::where('userId',$user_id)->get();
            $success = 'Leaves';
            return $this->sendJsonResponse($success, $products);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function checkin(Request $request){ 
        try {
            $validatedData = Validator::make($request->all(), [
                'location' => 'required',
                'photo' => 'required',
                'checkin' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $userId=auth()->user()->id;
            $photo = $request->file('photo');
            $photo_name = time() . "_" .$photo->getClientOriginalName();
            $destinationpath = public_path('uploads/');
            $photo->move($destinationpath,$photo_name);

            $input = $request->all();
            $input['uid'] = $userId;
            $input['status'] = "incomplete";
            $input['photo'] = $photo_name;
            $input['checkout'] = "";
            $user = Attendance::create($input);
            return response()->json(['msg' => 'You are checked in successfully.'], 403);
        } 
            catch (\Exception $e) {
                return $this->sendError('Error.', $e->getMessage());    
            }
    }
    public function checkout(Request $request){ 
        $user_id=auth()->user()->id;
        $leave=Attendance::find($user_id);
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'checkout' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->only('checkout');
        $data['status'] = "complete";

        $leave->update($data);

        return response()->json(['message' => 'You are checked out successfully']);
    }
    public function myAttendance(){
        try {
            $user_id=auth()->user()->id;
            $att = Attendance::where('uid',$user_id)->get();
            $success = 'Attendance';
            return $this->sendJsonResponse($success, $att);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
}