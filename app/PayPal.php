<?php

namespace App;

class PayPal {
    private $_apiContext;
    private $shopping_cart;
    private $_ClientId = 'Af32iF5Wi-wkzO9W4I-sn-afr7MBfUe_nuO1REVVIusOVkSCl6nldTJe19MXmP3JuPbrTSNSQpEsCpvt';
    private $_ClientSecret = 'EIDBwb-NIK6gGMoF3djhHlI9-CMsu8AXHE5I8h_7TxKU1V8TmVOeZ58CzFiNocEIXBKn5V2ZHIvLGVNs';

    public function __construct($shopping_cart)
    {
        $this->_apiContext = \PaypalPayment::ApiContext($this->_ClientId, $this->_ClientSecret); //PayPalPayment es el alias

        $config = config('paypal_payment'); //Nombre del archivo donde se encuentra la configuración
        $flatConfig = array_dot($config); // transformamos la configuracion en un arreglo plano

        $this->_apiContext->setConfig($flatConfig); //Listo para hacer llamadas al API
        
        $this->shopping_cart = $shopping_cart;
    }
 
    public function generate()
    {
        $payment = \PaypalPayment::payment()->setIntent("sale")->setPayer($this->payer()) //El objetivo de esta petición es vender
                   ->setTransactions([$this->transaction()])
                   ->setRedirectUrls($this->redirectURLs());

                   try{
                       $payment->create($this->_apiContext);
                   }catch(\Exception $ex)
                   {
                       dd($ex);
                       exit(1);
                   }

                   return $payment;
    }

    public function payer() //Retorna la información del pago
    {
        return \PaypalPayment::payer()->setPaymentMethod('paypal'); //Indicamos que el método de pago es paypal
    }

    public function transaction() //Retorna la informaci'on de la transacción'
    {
        return \PaypalPayment::transaction()
               ->setAmount($this->amount()) //Monto total a cobrar
               ->setItemList($this->items()) // Indicamos a PayPal que cobrara este array de articulos
               ->setDescription('Tu compra en Laravel Store')
               ->setInvoiceNumber(uniqid());
    }

    public function redirectURLs() // Redirecionamiento para pago efectivo o pago cancelado
    {
        $baseURL = url('/');
        return \PayPalPayment::redirectUrls()->setReturnUrl("$baseURL/payments/store")->setCancelUrl("$baseURL/carrito");
    }

    public function items() //Array de items a cobrar
    {
        $items = [];
        $products = $this->shopping_cart->products()->get();

        foreach ($products as $product)
        {
            array_push($items,$product->paypalItem());
        }

        return \PaypalPayment::itemList()->setItems($items);
    }

    public function amount() //Cantidad a cobrar
    {
        return \PaypalPayment::amount()->setCurrency('USD')->setTotal($this->shopping_cart->totalUSD()); //Cantidad total a cobrar en dólares
    }

    public function execute($paymentId,$payerId)
    {
        $payment = \PaypalPayment::getById($paymentId,$this->_apiContext); //Pasamos el ID del pago y los permisos 

        $execution = \PaypalPayment::PaymentExecution()->setPayerId($payerId); //Indicamos quien va a ejecutar el pago

        return $payment->execute($execution, $this->_apiContext); //En este caso execute() pertenece al API de PayPal
    }

}