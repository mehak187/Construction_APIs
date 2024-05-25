<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\PaymentIntent;
use App\Models\Payment;
use App\Models\Product;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Notifications\PaymentNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Traits\ApiResponseTrait;
use Validator;

class PaymentController extends Controller
{
    use ApiResponseTrait;
    public function processPayment(Request $request)
    { 
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'card_number' => 'required',
                'cvc' => 'required',
                'exp_month' => 'required',
                'exp_year' => 'required',
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $productID = $request->input('product_id');
            $cardNumber = $request->input('card_number');
            $cvc = $request->input('cvc');
            $expMonth = $request->input('exp_month');
            $expYear = $request->input('exp_year');

            // Stripe configuration
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Create card token
            $cardToken = Token::create([
                'card' => [
                    'number' => $cardNumber,
                    'exp_month' => $expMonth,
                    'exp_year' => $expYear,
                    'cvc' => $cvc,
                ],
            ]);

            $product = Product::find($productID);
            // Create Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => 100*$product->current_price, // Replace with the actual amount in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $cardToken->id,
                    ],
                ],
            ]);
            // Confirm the Payment Intent
            $paymentIntent->confirm();

            // Save payment in the database
            $payment = new Payment();
            $payment->product_id = $productID;
            $payment->user_id = auth()->user()->id; // Assuming you're using authentication
            $payment->save();
                // Send notification to the product owner
                $owner = $product->user;
                $notificationData = [
                    'message' => 'A payment has been made for your product: ' . $product->name,
                    'payment_id' => $payment->id,
                ];
                $owner->notify(new PaymentNotification($notificationData));
            // Generate QR code
            $qrcodeData = 'Product: ' . $product->name . ', Payment ID: ' . $payment->id; // Adjust as needed
            
            // Generate the QR code and save it as an image
        
            $qrcodePath = public_path('qrcodes/' . $payment->id . '.svg');
            QrCode::format('svg')->generate($qrcodeData, $qrcodePath);
            $data['payment']=Payment::with('product.user')->where('id',$payment->id)->first();
            $data['qrcode']=$data['payment']->qrcode_url;
            $success="Payment Successful";
            return $this->sendJsonResponse($success, $data);
        } 
        catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function purchasedHistory(){
        try {
            $data['purchased_items']=Payment::where('user_id',auth()->user()->id)->with('product')->get();
            $success="Purchased History";
            return $this->sendJsonResponse($success, $data);
        } 
        catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function singlePurchased($id){
        try {
            $data['purchased_items']=Payment::find($id)->with('product.user')->get();
            $success="Purchased History";
            return $this->sendJsonResponse($data, $success);
        } 
        catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
}
