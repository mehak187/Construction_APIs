<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Http\Traits\ApiResponseTrait;
use Validator;
class MerchantController extends Controller
{
    use ApiResponseTrait;
    public function storeMerchant(Request $request){ 
        try {
            // Validate the incoming request data
            $validatedData = Validator::make($request->all(), [
                'user_id' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'address' => 'required',
                'city' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'type_of_activity' => 'required',
                'contact' => 'required'
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            // // Create a new merchant instance and store the data
            $merchant = Merchant::create([
                'user_id' => $request->input('user_id'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                // 'latitude' => $request->input('latitude'),
                // 'longitude' => $request->input('longitude'),
                // 'type_of_activity' => $request->input('type_of_activity'),
                'contact' => $request->input('contact')
            ]);
            // // Send a message using Twilio
            // $twilioSid = 'YOUR_TWILIO_SID';
            // $twilioToken = 'YOUR_TWILIO_AUTH_TOKEN';
            // $twilioFromNumber = 'YOUR_TWILIO_PHONE_NUMBER';
        
            // $twilioClient = new Client($twilioSid, $twilioToken);
        
            // $message = $twilioClient->messages->create(
            //     $validatedData['contact'],
            //     [
            //         'from' => $twilioFromNumber,
            //         'body' => 'Your message content goes here'
            //     ]
            // );
        
            // // Return a response indicating success or failure
            // if ($message->sid) {
                return response()->json(['message' => 'Data stored and message sent.']);
            // } else {
            //     return response()->json(['message' => 'Failed to send message.'], 500);
            // }
        } 
            catch (\Exception $e) {
                return $this->sendError('Error.', $e->getMessage());    
            }
        }
}
