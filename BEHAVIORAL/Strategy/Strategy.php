<?php
/*
|---------------------------------------------------------------
| Strategy
| Definition:
| Strategy is a behavioral design pattern that turns a set of behaviors into objects and makes them interchangeable inside original context object
| The original object, called context, holds a reference to a strategy object and delegates it executing the behavior. 
| In order to change the way the context performs its work, other objects may replace the currently linked strategy object with another one.
|
| Author:
| RefactoringGuru
|---------------------------------------------------------------
*/

require_once('refactoringGuru/PaymentMethod.php');
require_once('refactoringGuru/Order.php');
require_once('refactoringGuru/CreditCardPayment.php');
require_once('refactoringGuru/PaypalPayment.php');
require_once('refactoringGuru/PaymentFactory.php');

class OrderController
{
    public function post(string $url, array $data)
    {
        echo "Controller: POST request to $url with " . json_decode($data) . "<br/>";
        $path = parse_url($url, PHP_URL_PATH);
        if( preg_match('#^/orders?$#', $path, $matches) ) {
            $this->postNewOrder($data);
        } else {
            echo "Controller: 404 page <br/>";
        }
    }
    
    public function get(string $url): void
    {
        echo "Controller: GET request to $url <br/>";
        $path = parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $data);

        if( preg_match('#^/orders?$#', $path, $matches) ) {
            $this->getAllOrders();
        } elseif( preg_match('#^/order/([0-9]+?)/payment/([a-z]+?)(/return)?$#', $path, $matches) ) {
            $order = Order::get($matches[1]);

            $paymentMethod = PaymentFactory::getPaymentMethod($matches[2]);

            if( ! isset($matches[3]) ) {
                $this->getPayment($paymentMethod, $order, $data);
            } else {
                $this->getPaymentReturn($paymentMethod, $order, $data);
            }
        } else {
            echo "Controller: 404 page <br/>";
        }
    }

    public function postNewOrder(array $data) 
    {
        $order = new Order($data);
        echo "Controller: Created the order #{$order->id}. <br/>";
    }

    public function getAllOrders(): void 
    {
        echo "Controller: Here's all orders <br/>";
        foreach(Order::get() as $order){
            echo json_encode($order, JSON_PRETTY_PRINT) . "<br/>";
        }
    }

    public function getPayment(PaymentMethod $method, Order $order, array $data): void
    {
        // The actual work is delegated to the payment method object.
        $form = $method->getPaymentForm($order);
        echo "Controller: here's the payment form: <br/>";
        echo $form . " <br/>";
    }

    public function getPaymentReturn(PaymentMethod $method, Order $order, array $data): void
    {
        try {
            if($method->validateReturn($order, $data)) {
                echo "Controller: Thanks for your order! <br/>";
                $order->complete();
            }
        } catch (\Exception $e) {
            echo "Controller: got an exception (" . $e->message() . ") <br/>";
        }
    }
}

#Usage
$controller = new OrderController;

echo "Client: Let's create some orders <br/>";

# 1
$controller->post("/orders", [
    "email"   => "me@example.com",
    "product" => "ABC cat food (XL)",
    "total"   => 9.95,
]);
# 2
$controller->post("/orders", [
    "email"   => "me@example.com",
    "product" => "XYZ cat litter (XXL)",
    "total"   => 19.95,
]);

echo "<br/> Client: List my orders, please <br/>";

$controller->get("/orders");

echo "<br/> Client: I'd like to pay for the second, show me the payment form <br/>";

echo "<br/>";

$controller->get("/order/1/payment/cc");

echo "<br/>";

$controller->get("/order/1/payment/cc/return" .
    "?key=c55a3964833a4b0fa4469ea94a057152&success=true&total=19.95");

