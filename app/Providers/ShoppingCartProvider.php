<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ShoppingCart;

class ShoppingCartProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer("*",function($view){
            $shopping_cart_id = \Session::get('shopping_cart_id'); //La 1a vez que accede el usuario esta variable es null

            $shopping_cart = ShoppingCart::findOrCreateBySessionID($shopping_cart_id); //si el argumento es null se crea el carrito

            \Session::put('shopping_cart_id',$shopping_cart->id); //guardamos el id del carrito en el server 

            $view->with('productsCount',$shopping_cart->productsCount()); //Enviar en este variable el carrito de compras
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
