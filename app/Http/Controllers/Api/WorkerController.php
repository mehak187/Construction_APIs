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
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office worker can apply for leave'], 403);
            }
            $validatedData = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required|string',
                'timeAdded' => 'required',
                'attachment' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
                'leaveType' => 'required|string',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office workers can apply for leave.'], 403);
            }
            $userId = auth()->user()->id;
            $photo = $request->file('attachment');
            
            // Store the attachment
            $photo_name = time() . "_" . $photo->getClientOriginalName();
            $destinationPath = public_path('uploads/');
            $photo->move($destinationPath, $photo_name);
    
            // Prepare the input data
            $input = $request->all();
            $input['userId'] = $userId;
            $input['status'] = "pending";
            $input['attachment'] = $photo_name;
    
            // Create the leave request
            $leave = Leave::create($input);
    
            return response()->json(['msg' => 'Leave request sent successfully.'], 201);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => 'Error.', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function myLeaves(){
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office worker can view their leaves'], 403);
            }
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
                'checkinPhoto' => 'required',
                'checkin' => 'required',
                'project_name' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $user = Auth::user();
            if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
                return response()->json(['error' => 'Only site workers and supervisers can post attendence.'], 403);
            }
            $userId=auth()->user()->id;
            $checkinPhoto = $request->file('checkinPhoto');
            // $checkoutPhoto = $request->file('checkoutPhoto');
            // ------for live---------
            // $liveURL = "http://constructionapp.wantar-system.com/uploads/";
            // $checkinPhoto_name = $liveURL . time() . "_" . $checkinPhoto->getClientOriginalName();
            $checkinPhoto_name = time() . "_" .$checkinPhoto->getClientOriginalName();
            $destinationpath = public_path('uploads/');
            $checkinPhoto->move($destinationpath,$checkinPhoto_name);
            

            // ------for live---------
            // $liveURL = "http://constructionapp.wantar-system.com/uploads/";
            // $checkoutPhoto_name = $liveURL . time() . "_" . $checkoutPhoto->getClientOriginalName();
            // $checkoutPhoto_name = time() . "_" .$checkoutPhoto->getClientOriginalName();
            // $destinationpath = public_path('uploads/');
            // $checkoutPhoto->move($destinationpath,$checkoutPhoto_name);

            $input = $request->all();
            $input['uid'] = $userId;
            $input['status'] = "incomplete";
            $input['checkinPhoto'] = $checkinPhoto_name;
            $input['checkout'] = "";
            $input['checkoutPhoto'] = "";
            $user = Attendance::create($input);
            return response()->json(['msg' => 'You are checked in successfully.'], 201);
        } 
            catch (\Exception $e) {
                return $this->sendError('Error.', $e->getMessage());    
            }
    }
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'checkout' => 'required',
            'checkoutPhoto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $id = $request->id;
        $login_id = auth()->user()->id;;
        $leave = attendance::where('uid', $login_id)->where('id', $id)->first();
        if (!$leave) {
            return response()->json(['error' => 'Attendance record not found'], 404);
        }
        $checkoutPhoto = $request->file('checkoutPhoto');
        $liveURL = "http://constructionapp.wantar-system.com/uploads/";
        $checkoutPhoto_name = $liveURL . time() . "_" . $checkoutPhoto->getClientOriginalName();
        $destinationPath = public_path('uploads/');
        $checkoutPhoto->move($destinationPath, $checkoutPhoto_name);
    
        // Update the leave record
        $leave->checkout = $request->checkout;
        $leave->overtime = $request->overtime;
        $leave->checkoutPhoto = $checkoutPhoto_name;
        $leave->status = "complete";
        $leave->save();
    
        return response()->json(['message' => 'You are checked out successfully']);
    }
    
    public function myAttendance(){
        try {
            $user = Auth::user();
            if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
                return response()->json(['error' => 'Only site workers and supervisers can post and view their attendence.'], 403);
            }
            $user_id=auth()->user()->id;
            $att = Attendance::where('uid',$user_id)->get();
            $success = 'Attendance';
            return $this->sendJsonResponse($success, $att);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
}