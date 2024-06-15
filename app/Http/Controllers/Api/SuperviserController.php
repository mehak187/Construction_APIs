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

class SuperviserController extends Controller
{
    use ApiResponseTrait;
    public function allAttendance(){
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'You do not have access to view attendance'], 403);
            }
            $data = attendance::leftJoin('nickyClockinSystem_users', 'attendances.uid', '=', 'nickyClockinSystem_users.id')
            ->select(
                'nickyClockinSystem_users.name as uname',
                'nickyClockinSystem_users.role as role',
                'nickyClockinSystem_users.lastname',
                'nickyClockinSystem_users.staff_id',
                'attendances.*',
            )
            ->orderBy('attendances.id', 'desc')
            ->get();

            $success = 'Attendance';
            return $this->sendJsonResponse($success, $data);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
}
