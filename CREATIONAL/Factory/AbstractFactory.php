<?php
/*
|----------------------------------------------------------------------------------------
|   Title: Abstract Factory
|----------------------------------------------------------------------------------------
|   Author: kamranahmedse
|   Defintion: 
|       The abstract factory pattern provides a way to encapsulate a group of individual factories that have a common theme without specifying their concrete classes
|       A factory of factories; a factory that groups the individual but related/dependent factories together without specifying their concrete classes.
|    
|----------------------------------------------------------------------------------------
*/


# Extending Simplefactory concepts
require_once('SimpleFactory.php');
echo '<br/>';
echo '<br/>';

# Create iron door extending door from simple factory exercise.
class IronDoor implements Door
{
    public function getWidth(): float
    {
        return 0;
    }
    public function getHeight(): float
    {
        return 0;
    }
    public function getDefinition(): string
    {
        return 'I am an Iron Door';
    }
}
# Create a factory of Door Expert
interface DoorFittingExpert 
{   
    public function getDescription();
}
# Iron Expert
class Welder implements DoorFittingExpert
{
    public function getDescription()
    {
        return 'I can only fit iron door.';
    }
}
# Wooden Expert
class Carpenter implements DoorFittingExpert
{
    public function getDescription()
    {
        return 'I can only fit wooden door.';
    }
}
# After that create a factory the will manage which type of doors(wooden,iron) and experts(welder,carpenter)
interface DoorsFactory
{
    public function makeDoor(): Door;
    public function makeFittingExpert(): DoorFittingExpert;
}
# Wooden Factory
class WoodenDoorFactory implements DoorsFactory {
    public function makeDoor(): Door
    {
        return new WoodenDoor(100, 200);
    }
    public function makeFittingExpert(): DoorFittingExpert
    {
        return new Carpenter();
    }
}
class IronDoorFactory implements DoorsFactory
{
    public function makeDoor(): Door
    {
        return new IronDoor();
    }
    public function makeFittingExpert(): DoorFittingExpert
    {
        return new Welder();
    }
}
# Usage
$factory = new WoodenDoorFactory();
$door    = $factory->makeDoor();
$expert   = $factory->makeFittingExpert();

echo $door->getDefinition() . '<br/>';
echo $expert->getDescription();

echo '<br/>';
echo '<br/>';

$factory = new IronDoorFactory();
$door    = $factory->makeDoor();
$expert   = $factory->makeFittingExpert();

echo $door->getDefinition() . '<br/>';
echo $expert->getDescription();