<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShoppingCart;
use App\PayPal;

class ShoppingCartsController extends Controller
{
    public function index()
    {
        $shopping_cart_id = \Session::get('shopping_cart_id');

        $shopping_cart = ShoppingCart::findOrCreateBySessionID($shopping_cart_id);

        $products = $shopping_cart->products()->get(); //Conseguimos los productos en el carrito con la relación products()

        $total = $shopping_cart->total();

        $paypal = new PayPal($shopping_cart);

        $payment = $paypal->generate();

        return redirect($payment->getApprovalLink());

        //return view('shopping_carts.index',["products"=>$products,"total"=>$total]);
    }
}
