<?php
/*
|----------------------------------------------------------------------------------------
|   Title: Simple Factory
|----------------------------------------------------------------------------------------
|   Author: kamranahmedse
|   Defintion: 
|    Simple factory simply generates an instance for client without
|    exposing any instantiation logic to the client
|----------------------------------------------------------------------------------------
*/

interface Door
{
    public function getWidth(): float;
    public function getHeight(): float;
    public function getDefinition(): string;
}

# Wooden Door implements Door
class WoodenDoor implements Door 
{
    protected $width;
    protected $height;

    public function __construct(float $width, float $height){
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }
    public function getHeight(): float
    {
        return $this->height;
    }
    public function getDefinition(): string
    {
        return 'I am a wooden Door';
    }
}
# Create your factory 
class DoorFactory
{
    public static function makeDoor($width, $height): Door {
        return new WoodenDoor($width, $height);
    }
}
# Usage
$door1 = DoorFactory::makeDoor(100, 200);
$door2 = DoorFactory::makeDoor(50, 100);

echo 'width' . $door1->getWidth() . '<br/>';
echo 'height' .$door1->getHeight();
