<?php

class PaymentFactory 
{
    public static function getPaymentMethod(string $id): PaymentMethod
    {
        switch($id) {
            case "cc":
                return new CreditCardPayment;
            break;
            case "paypal":
                return new PaypalPayment;
            break;
            default:
                throw new \Exeption("Unknown Payment Method.");
        }
    }
}

