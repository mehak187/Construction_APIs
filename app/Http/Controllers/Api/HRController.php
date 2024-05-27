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
use Validator;
use Mail;
use Illuminate\Support\Str;

class HRController extends Controller
{
    use ApiResponseTrait;
    public function allLeaves(){
        try {
            $data = Leave::leftJoin('nickyclockinsystem_users', 'nickyclockinsystem_leaves.userid', '=', 'nickyclockinsystem_users.id')
            ->select(
                'nickyclockinsystem_users.name as uname',
                'nickyclockinsystem_users.lastname',
                'nickyclockinsystem_users.staff_id',
                'nickyclockinsystem_leaves.*',
            )
            ->orderBy('nickyclockinsystem_leaves.id', 'desc')
            ->get();


            $success = 'Leaves';
            return $this->sendJsonResponse($success, $data);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function updateLeaveStatus(Request $request)
    {
        $leave=Leave::find($request->id);
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->only('status');

        $leave->update($data);

        return response()->json(['message' => 'Leave status updated successfully']);
    }
}
