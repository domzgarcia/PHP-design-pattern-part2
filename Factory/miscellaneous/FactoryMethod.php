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


abstract class SocialNetworkPoster 
{
    private $email, $password;
    abstract public function getConnector(): SocialNetworkConnector;
    
    public function post($content){
        $connector = $this->getConnector();
        $connector->login();
        $connector->createPost($content);
        $connector->logOut();
    }
}

class FacebookPoster extends SocialNetworkPoster
{
    private $email, $password;
    public function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
    }
    public function getConnector(): SocialNetworkConnector
    {
        return new FacebookConnector($this->email, $this->password);
    }
}

class LinkedInPoster extends SocialNetworkPoster
{
    private $email, $password;
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    public function getConnector(): SocialNetworkConnector
    {
        return new LinkedInConnector($this->email, $this->password);
    }
}


function execution(SocialNetworkPoster $creator): void
{
    $creator->post("Hello World!");
}

# execute your code
execution(new FacebookPoster("johndoe@facebook.com","******"));

echo '<br/>';

execution(new LinkedInPoster("johndoe@linkedin.com","******"));