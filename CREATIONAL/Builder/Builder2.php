<?php
/*
|---------------------------------------------------------------
| Builder
| Definition:
| Allows you to create different flavors of an object while avoiding constructor pollution. Useful when there could be 
| several flavors of an object. Or when there are a lot of steps involved in creation of an object.
|
| Wiki:
| The builder pattern is an object creation software design pattern with the intentions of finding a solution to the telescoping 
| constructor anti-pattern
| 
| Author:
| kamranahmedse
|---------------------------------------------------------------
*/
class Burger 
{
    protected $size;

    protected $cheese = false;
    protected $peperoni = false;
    protected $lettuce = false;
    protected $tomato = false;

    public function __construct(BurgerBuilder $builder)
    {
        $this->size = $builder->size;
        $this->cheese = $builder->cheese;
        $this->peperoni = $builder->peperoni;
        $this->lettuce = $builder->lettuce;
        $this->tomato = $builder->tomato;
    }
}

class BurgerBuilder 
{
    public $size;
    
    public $cheese = false;
    public $peperoni = false;
    public $lettuce = false;
    public $tomato = false;

    public function __construct(int $size)
    {
        $this->size = $size;
    }
    public function addPeperoni()
    {
        $this->peperoni = true;
        return $this;
    }
    public function addLettuce()
    {
        $this->lettuce = true;
        return $this;
    }
    public function addCheese()
    {
        $this->cheese = true;
        return $this;
    }
    public function addTomato()
    {
        $this->tomato = true;
        return $this;
    }
    public function build(): Burger
    {
        // this just pass all arguments $this Class has
        return new Burger($this);
    }
}

$burger = (new BurgerBuilder(14))
    ->addPeperoni()
    ->addCheese()
    ->addLettuce()
    ->addTomato()
    ->build();

echo '<pre>';
print_r($burger);


# Builder VS Factory
# When there could be several flavors of an object and to avoid the constructor telescoping. The key difference from the 
# factory pattern is that; factory pattern is to be used when the creation is a one step process while builder pattern is to be 
# used when the creation is a multi step process.