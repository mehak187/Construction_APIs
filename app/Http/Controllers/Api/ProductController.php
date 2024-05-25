<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Product;
use App\Models\User;
use App\Models\VoteLimit;
use App\Models\VoteProduct;
use App\Models\Promotion;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\PaymentIntent;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VotePercentageReachedNotification;

class ProductController extends Controller
{
    use ApiResponseTrait;
    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image',
            'current_price' => 'required',
            'weight' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = auth()->user();

        $imagePath = $request->file('image')->store('product_images', 'public');

        $product = new Product([
            'name' => $request->input('name'),
            'image' => $imagePath,
            'current_price' => $request->input('current_price'),
            'weight' => $request->input('weight'),
            'user_id' => $user->id,
        ]);

        $product->save();

        return response()->json(['message' => 'Product saved successfully'], 201);
    }
    // Controller method to update a product
    public function updateProduct(Request $request)
    {
        $product=Product::find($request->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'current_price' => 'required',
            'weight' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->only('name', 'current_price', 'weight');

        if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $imagePath = $request->file('image')->store('product_images', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return response()->json(['message' => 'Product updated successfully']);
    }
    public function deleteProduct($id)
    {
        $product=Product::find($id);
        $imagePath = $product->image;

        $product->delete();

        if (!empty($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function products(){
        try {
            $user_id=auth()->user()->id;
            $products = Product::withCount('vote')
            ->withCount(['vote as myvote' => function ($query) use ($user_id)    {
                $query->where('user_id', '=', $user_id);
           }])->get();
            $votingLimit = VoteLimit::first()->limit; // Assuming there's only one row in the VoteLimit table
            foreach ($products as $product) {
                $totalVotes = $product->vote_count;
                $votePercentage = round(($totalVotes / $votingLimit) * 100, 2);
                $product->vote_percentage = $votePercentage;
            }  
            $success = 'Products List';
            return $this->sendJsonResponse($success, $products);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function singleProduct($id){
        try {
            $user_id=auth()->user()->id;
            $product = Product::find($id);
            $votingLimit = VoteLimit::first()->limit; // Assuming there's only one row in the VoteLimit table
            $totalVotes = $product->vote_count;
            $votePercentage = round(($totalVotes / $votingLimit) * 100, 2);
            $product->vote_percentage = $votePercentage; 
            $success = 'Product Detail';
            return $this->sendJsonResponse($success, $product);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function voteProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $check = VoteProduct::where([
            'user_id' => auth()->user()->id,
            'product_id' => $request->get('product_id'),
        ])->count();

        if ($check > 0) {
            return response()->json(['message' => 'Rating Already Added']);
        }

        $product = Product::find($request->get('product_id'));
        VoteProduct::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->get('product_id'),
        ]);

        $votingLimit = VoteLimit::first()->limit;
        $totalVotes = $product->vote_count;
        $votePercentage = round(($totalVotes / $votingLimit) * 100, 2);

        if ($votePercentage >= 100) {
            $owner = $product->user; // Assuming the product owner is associated with a user relationship in the Product model
            $notificationData = [
                'message' => 'Vote percentage for your product has reached 100%.',
                'product_id' => $product->id,
            ];
            $owner->notify(new VotePercentageReachedNotification($notificationData));
        }

        return response()->json(['message' => 'Rating Added Successfully']);
    }
    public function addPromotion(Request $request){
        
    try {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'valid_period' => 'required',
            'card_number' => 'required',
            'cvc' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
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
            // Create Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => 100*$request->get('amount'), // Replace with the actual amount in cents
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

        $user = auth()->user();
        // $check=Promotion::where([
        //     'user_id'=>auth()->user()->id,
        //     'product_id'=>$request->get('product_id'),
        // ])->get()->count();
        // if($check>0){
        //     return response()->json(['message' => 'Promotion Already Saved']);   
        // }
        // else{ }
            $promotion = new Promotion([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'valid_period' => $request->valid_period,
            ]);
            $promotion->save();
            return response()->json(['message' => 'Promotion saved successfully'], 201);
        
    } 
    catch (\Exception $e) {
        return $this->sendError('Error.', $e->getMessage());    
    }
    }
    public function getPromotion(){
        try {
            $promotions = Promotion::where('status','approved')
            ->whereDate('valid_period', '>', date('Y-m-d'))->with('product')->get();
            $votingLimit = VoteLimit::first()->limit; // Assuming there's only one row in the VoteLimit table
            foreach ($promotions as $promotion) {
                $product = $promotion->product;
                $totalVotes = $product->vote_count;
                $votePercentage = round(($totalVotes / $votingLimit) * 100, 2);
                $product->vote_percentage = $votePercentage;
            }
            $success = 'Products List';
            return $this->sendJsonResponse($success, $promotions);
        } 
        catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
}
