<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = ["status"]; //Campo modificable exclusivo para metodo ShoppingCart::create()

    //Relaciones
    public function inShoppingCarts() //Tabla pivot
    {
        return $this->hasMany('App\InShoppingCart');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product','in_shopping_carts'); //Nombre de la tabla en la BD
    }

    public function productsCount()
    {
        return $this->products()->count();
    }

    public function total()
    {
        return $this->products()->sum('pricing'); //Sumamos todo el campo pricing
    }

    public function totalUSD()
    {
        return $this->products()->sum('pricing') / 100;
    }

    public static function findOrCreateBySessionID($shopping_cart_id)
    {
        if($shopping_cart_id) //Buscar el carrito de compras con este ID
        {
            return ShoppingCart::findBySession($shopping_cart_id);
        }else{ //Si no existe creamos uno
            return ShoppingCart::createWithoutSession();
        }
    }

    public static function findBySession($shopping_cart_id)
    {
        return ShoppingCart::find($shopping_cart_id);
    }

    public static function createWithoutSession()
    {
        return ShoppingCart::create([ //Creando carrito
            "status" => "incompleted"
        ]);
    }
}
