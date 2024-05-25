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
    public function registerUser(Request $request)
        {
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
            $input['role'] = 'user';
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
    public function registerMerchant(Request $request)
        {
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
    public function login(Request $request)
        {
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
                    $data['id'] =  $user->id;
                    $data['name'] =  $user->name;
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
        
    public function redirectToFacebook()
        {
            $state = bin2hex(random_bytes(32));
            Session::put('state', $state);
            $scopes = ['email', 'public_profile']; // Add any additional required scopes
            return Socialite::driver('facebook')
                ->scopes($scopes)
                ->stateless()
                ->redirectUrl('/login/facebook/callback') // Add the callback route URL here
                ->with(['state' => $state])
                ->redirect();
        }
    public function handleFacebookCallback(Request $request)
        {
            try{
            $accessToken=$request->get('access_token');
            $user = Socialite::driver('facebook')->userFromToken($accessToken);
            $existingUser = User::where('email', $user->email)->first();
            $count=[];
            if(isset($existingUser)){
                $count=$existingUser->toArray();
            }
            if (count($count)>0) {
                Auth::login($existingUser);
                $data['token'] =  $existingUser->createToken('MyApp')->plainTextToken; 
                $data['id'] =  $existingUser->id;
                $data['name'] =  $existingUser->name;
                $data['role'] =  $existingUser->role;
                return $this->sendJsonResponse('User login successfully.',$data); 
            } else {
                $newUser = new User();
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->password = bcrypt(rand(0, 9));
                $newUser->save();
                Auth::login($newUser);
                $data['token'] =  $newUser->createToken('MyApp')->plainTextToken; 
                $data['id'] =  $newUser->id;
                $data['name'] =  $newUser->name;
                $data['role'] =  $newUser->role;
                return $this->sendJsonResponse('User Registered successfully.',$data);
            }
        } catch (\Exception $e) {
                return $this->sendAuthError('Error.', $e->getMessage());    
            }
        }
    public function redirectToGoogle()
        {
            return Socialite::driver('google')->redirect();
        }
        public function handleGoogleCallback(Request $request)
        {
            try{
                
            $accessToken=$request->get('access_token');
            $user = Socialite::driver('google')->userFromToken($accessToken);
            // Check if the user already exists in your database
            $existingUser = User::where('email', $user->email)->first();
            $count=[];
            if(isset($existingUser)){
                $count=$existingUser->toArray();
            }
            if (count($count)>0) {
                Auth::login($existingUser);
                $data['token'] = $existingUser->createToken('MyApp')->plainTextToken; 
                $data['id']    = $existingUser->id;
                $data['name']  = $existingUser->name;
                $data['role']  = $existingUser->role;
                return $this->sendJsonResponse('User login successfully.',$data); 
             } else {
                $altername = str_replace('@gmail.com', '', $user->email);
                $newUser = new User();
                $newUser->name  = $user->name ?? $altername;
                $newUser->email = $user->email;
                $newUser->password = bcrypt(rand(0, 9));
                $newUser->save();
                Auth::login($newUser);
                $data['token'] =  $newUser->createToken('MyApp')->plainTextToken; 
                $data['id']    =  $newUser->id;
                $data['name']  =  $newUser->name;
                $data['role']  =  $newUser->role;
                return $this->sendJsonResponse('User Registered successfully.',$data);
            } 
            }catch (\Exception $e) {
                return $this->sendAuthError('Error.', $e->getMessage());    
            }
        }
}