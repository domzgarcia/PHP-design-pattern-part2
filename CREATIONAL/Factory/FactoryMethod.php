<?php
/*
|----------------------------------------------------------------------------------------
|   Title: Factory Method
|----------------------------------------------------------------------------------------
|   Author: kamranahmedse
|   Defintion: 
|       It provides a way to delegate the instantiation logic to child classes.
|       A creational pattern that uses method to deal with the problem of creating objects
|       w/o having to specify the exact class of the object that will be created.
|
| ~ Should know what is a child class
|----------------------------------------------------------------------------------------
*/
interface InterviewerStep
{
    public function askQuestions();
}
# Create developer interview steps
class DeveloperStep implements InterviewerStep
{
    public function askQuestions()
    {
        echo 'Asking about design patterns.';
    }
}
# Create Community Executive interview steps
class CommunityExcecutiveStep implements InterviewerStep
{
    public function askQuestions()
    {
        echo 'Asking about community building.';
    }
}
class DesignerStep implements InterviewerStep
{
    public function askQuestions()
    {
        echo 'Asking about designer concepts.';
    }
}
# Hiring Manager with Factory method
abstract class HiringManager
{
    private $steps;
    # abstract {method}
    abstract protected function makeInterviewerStep(): InterviewerStep;
    public function takeInterview()
    {
        $steps = $this->makeInterviewerStep();
        $steps->askQuestions();
    }
} 
# Delegate to Developer Management the interviewer steps
class DevelopmentManager extends HiringManager
{
    protected function makeInterviewerStep(): InterviewerStep
    {
        return new DeveloperStep();
    }
}
class DesignerManager extends HiringManager
{
    protected function makeInterviewerStep(): InterviewerStep
    {
        return new DesignerStep();
    }
}
# Delegate to Marketing Manager the interviewer steps
class MarketingManager extends HiringManager
{
    protected function makeInterviewerStep(): InterviewerStep
    {
        return new CommunityExcecutiveStep();
    }
}

# usage
$developmentManager = new DevelopmentManager();
echo $developmentManager->takeInterview() . '<br/>';

$marketingManager = new MarketingManager();
echo $marketingManager->takeInterview() . '<br/>';

$designerManager = new DesignerManager();
echo $designerManager->takeInterview();







