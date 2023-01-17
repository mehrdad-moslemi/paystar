<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PaymentController extends Controller
{
    public $callback , $key , $terminal_id;

    public function __construct(){
        $this->callback = route('payment.callback');
        $this->key = config('services.paystar.key');
        $this->terminal_id = config('services.paystar.gateway_id');
    }

    public function createPayment(){
        $product = Product::findOrFail(request()->cookie('productId'));

        if(! $product || is_null($product)) return redirect()->route('all-products')->with('error' , 'There is no product to buy');

        // paystar create payment
        try {
            $order = auth()->user()->orders()->create([
                'total_amount' => $product->price
            ]);
            $order->products()->create([
                'product_id' => $product->id,
                'price' => $product->price
            ]);
            
            $amount = $order->total_amount;
            $sign = hash_hmac('SHA512' , $amount . '#' . $order->id . '#' . $this->callback , $this->key);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://core.paystar.ir/api/pardakht/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>
                    json_encode([
                        'amount' => $amount,
                        'order_id' => $order->id,
                        'callback' => $this->callback ,
                        'sign' => $sign,
                        'callback_method' => 1
                    ]),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' .  $this->terminal_id,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);

            if($response->status == 1){
                $order->payments()->create([
                    'ref_num' => $response->data->ref_num,
                    'payment_amount' => $response->data->payment_amount
                ]);
    
                return redirect()->route('payment.payment')->with('token' , $response->data->token);
            }else{
                return redirect()->route('all-products')->with('error' ,  convertPaystarStatusCode($response->status));
            }

        } catch (\Exception $e) {
            return back()->with( 'error' ,  'Something went wrong' );
            // debug
            // return back()->with( 'error' ,  json_encode($e->getMessage()) );
        }
    }

    public function payment(){
        $token = session('token');

        if(! $token) return redirect()->route('all-products');

        return view('pages.payment.payment' , compact('token'));
    }

    public function callback(Request $request){
        Cookie::forget('productId' , null);

        if($request->status != 1){
            return redirect()->route('payment.fail')->with('error' , convertPaystarStatusCode($request->status))->with('orderId' , $request->order_id);
        }

        $card_number_start_validation = substr($request->card_number , 0 , 6) == substr(auth()->user()->card_number , 0 , 6);
        $card_number_end_validation = substr($request->card_number , 12 , 16) == substr(auth()->user()->card_number , 12 , 16);

        if( ! auth()->user()->is_card_verified || ! $card_number_start_validation || ! $card_number_end_validation ){
            return redirect()->route('payment.fail')
                        ->with('error' , 'You are not allowed to use this card number or your card number is not verified, your order is canceled and the amount will be returned to your account soon.')
                        ->with('orderId' , $request->order_id);
        }

        if($payment = Payment::where('ref_num' , $request->ref_num)->first()){

            // paystar verify payment
            try {
                $sign = hash_hmac('SHA512' , $payment->payment_amount . '#' . $payment->ref_num . '#' . $request->card_number . '#' . $request->tracking_code , $this->key);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://core.paystar.ir/api/pardakht/verify',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>
                        json_encode([
                            'ref_num' => $payment->ref_num,
                            'amount' => $payment->payment_amount,
                            'sign' => $sign
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' .  $this->terminal_id,
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response);

                $payment->update([
                    'card_number' => $request->card_number,
                    'tracking_code' => $request->tracking_code,
                    'transaction_id' => $request->transaction_id,
                    'status' => 1
                ]);

                return redirect()->route('payment.success')->with('paymentId' , $payment->id);
                
            } catch (\Exception $e) {
                // return redirect()->route('all-products')->with('error' , 'Something went wrong');
                // debug
                return redirect()->route('all-products')->with('error' , json_encode($e->getMessage()) );
            }
            
        }else {
            return redirect()->route('all-products')->with('error' , 'No payment found');
        }

    }

    public function success(){
        if(session('paymentId') && $payment = Payment::findOrFail(session('paymentId'))){
            return view('pages.payment.success' , compact('payment'));
        }else{
            return redirect()->route('all-products');
        }
    }

    public function fail(){
        if($message = session('error')){
            $orderId = session('orderId');
            return view('pages.payment.fail' , compact(['message' , 'orderId']));
        }else{
            return redirect()->route('all-products');
        }
    }
    
}
