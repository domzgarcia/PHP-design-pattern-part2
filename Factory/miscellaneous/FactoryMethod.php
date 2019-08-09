<?php
/*
| Definition:
| Factory Method defines a method, which should be used for creating objects instead of direct constructor call (new operator). 
| Subclasses can override this method to change the class of objects that will be created.
|
| Author:
| RefactoringGuru
*/

interface SocialNetworkConnector 
{
    public function logIn(): void;
    public function logOut(): void;
    public function createPost($content): void;
}

abstract class SocialNetworkPoster
{
    abstract public function getSocialNetwork(): SocialNetworkConnector;

    public function post($post): void
    {   
        $network = $this->getSocialNetwork();
        $network->logIn();
        $network->createPost($post);
        $network->logOut();
    }
}

# Create Connectors
class FacebookConnector implements SocialNetworkConnector
{
    private $email, $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    public function login(): void
    {
        echo "Send HTTP API request to log in user $this->email with " .
            "password $this->password" . "<br />";
    }
    public function createPost($post): void
    {
        echo "Send HTTP API requests to create a post in Facebook timeline." . "<br />";
    }
    public function logOut(): void 
    {
        echo "Send HTTP API request to log out user $this->email" . "<br />";
    }
}

class LinkedInConnector implements SocialNetworkConnector 
{
    private $email, $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    public function logIn(): void {
        echo "Send HTTP API request to log in user $this->email with " .
            "password $this->password" . "<br />";
    }
    public function logOut(): void {
        echo "Send HTTP API request to log out user $this->email" . "<br />";
    }
    public function createPost($post): void {
        echo "Send HTTP API requests to create a post in LinkedIn timeline." . "<br />";
    }
}

# Create Posters
class FacebookPoster extends SocialNetworkPoster
{
    private $email, $password;

    public function __construct(string $email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new FacebookConnector($this->email, $this->password);
    }
}

class LinkedInPoster extends SocialNetworkPoster
{
    private $email, $password;

    public function __construct(string $email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new LinkedInConnector($this->email, $this->password);
    }
}

function execution(SocialNetworkPoster $creator): void
{
    $creator->post("Hello World!");
}

execution(new FacebookPoster("johndoe@tester.com","******"));
execution(new LinkedInPoster("johndoe@tester.com","******"));