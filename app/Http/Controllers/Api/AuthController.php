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


class AuthController extends Controller
{
    use ApiResponseTrait;
    public function registerWorker(Request $request) {
            \DB::beginTransaction();
            try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);
    
            if($validator->fails()){
                return $this->sendAuthError('Validation Error.', $validator->errors());       
            }
    
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['staff_id'] = Str::upper(Str::random(10));
            $user = User::create($input);
            
            $data['token'] =  $user->createToken('MyApp')->plainTextToken;
            \DB::commit();
            return $this->sendResponse('User register successfully.',$data);
            
            } catch (\Exception $e) {
                \DB::rollback();
                return $this->sendAuthError('Error.', $e->getMessage());    
            }

    }
    public function registerMerchant(Request $request){
            \DB::beginTransaction();
            try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendAuthError('Validation Error.', $validator->errors());       
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['role'] = 'merchant';
            $user = User::create($input);
            $data['token'] =  $user->createToken('MyApp')->plainTextToken;
            $data['name'] =  $user->name;
            $data['role'] =  $user->role;
            \DB::commit();
            return $this->sendResponse('User register successfully.',$data);
            
            } catch (\Exception $e) {
                \DB::rollback();
                return $this->sendAuthError('Error.', $e->getMessage());    
            }

    }
    public function login(Request $request) {
            try{
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required',
                ]);
                if($validator->fails()){
                    return $this->sendAuthError('Validation Error.', $validator->errors());       
                } 
                if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                    $user = Auth::user(); 
                    $data['token'] =  $user->createToken('MyApp')->plainTextToken; 
                    $data['id'] = $user->id;
                    $data['role'] =  $user->role;
                    return $this->sendJsonResponse('User login successfully.',$data);
                } 
                else{ 
                    return $this->sendAuthError('Invalid credentials.', ['error' => 'The provided email or password is incorrect.']);
                }
            } catch (\Exception $e) {
                return $this->sendAuthError('Error.', $e->getMessage());    
            }
    }  
}