<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Traits\ApiResponseTrait;
use File;
class ProfileController extends Controller
{
    use ApiResponseTrait;
    //
    public function getProfile(){
        try {     
            $data=User::find(auth()->user()->id);
            $success="User Profile";
            return $this->sendJsonResponse($data, $success);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function updateProfile(Request $request){
        try {     
            $data=User::where('id',auth()->user()->id)->update([
                'name'=>$request->name,
                'bio'=>$request->bio
            ]);
            $success="Profile Updated";
            return $this->sendJsonResponse($data, $success);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }  
    }

    public function changeProfile(Request $req){
        try {     
            //store selected img in path
            $photo = $req->file('profile');
            $photo_name = time() . "_" .$photo->getClientOriginalName();
            $destinationpath = public_path('myimgs/');
            $photo->move($destinationpath,$photo_name);

            $data=User::find($req->id);
            $img_path = public_path("myimgs/" .$data->photo);
            if(File::exists($img_path)) {
                File::delete($img_path);
            }
            User::find($req->id)->update([
                'image' => $photo_name
            ]);

            $success="Profile Updated";
            return $this->sendJsonResponse($data, $success);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }  
    }
    public function getData(){
        try { 
            $user = auth()->user(); // Assuming you want to get data for a specific user with ID 1, you can replace this with the user ID you need.

            if (!$user) {
                return $this->sendError('User not found.', []);
            }
    
            $numberOfProducts = $user->products()->count(); // Count the number of products associated with the user
            $referredMerchantCount = $user->referredMerchantsCount();
            $data = [
               'number_of_products' => $numberOfProducts,
               'buyers' => $referredMerchantCount
             ];
            $success="Data";
            return $this->sendJsonResponse($data, $success);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }  
    }
}
