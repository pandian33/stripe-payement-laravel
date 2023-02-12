<?php

namespace App\Http\Controllers;

use App\Repositories\ShopRepository;
use App\Traits\Utillity;
use Exception;
use Illuminate\Http\Request;
use Validator;
use Stripe;
//use Session;

class StripePaymentController extends Controller
{
    use Utillity;
    public function stripePost(Request $request)
    {
        try{
            //$stripe = new \Stripe\StripeClient('sk_test_51LkiN2BwTZH5dOjMkA1Z1UIXAE07TtuKnKV7MaLxeTONv5Xt8e97y5Mlk57VmtjtxXhIaPXPjQy2diMy30E7yQP400u61oHOI3');

            $params = $this->getData($_REQUEST);

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $token = $stripe->tokens->create([
                'card'  => [
                    'number'    => $params['cardNumber'],
                    'exp_month' => $params['expMonth'],
                    'exp_year'  => $params['expYear'],
                    'cvc'       => $params['csv'],
                ],
            ]);


            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $charge = \Stripe\Charge::create([
                    'amount'        =>  $params['amt'] * 100,
                    'currency'      => 'usd',
                    'source'        => $token,
                    'description'   => 'Bemodo Test',
                ]);

                
            
            // if($charge['status'] == 'succeeded') {
            //     return redirect('stripe')->with('success', 'Payment Success!');
 
            // } else {
            //     return redirect('stripe')->with('error', 'something went to wrong.');
            // }

           return response()->json([$charge['status']], 201);
        }catch(Exception $e){
            return response()->json([['response'=>'Error']], 500);
        }
    }
}
