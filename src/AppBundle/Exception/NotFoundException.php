<?php
namespace AppBundle\Exception;

class NotFoundException extends \Exception implements ExceptionInterface
{
    private $statusCode = 328;
    private $headers;
    private $title;

    public function __construct($uid)
    {
        $this->headers = array();
        $this->title = "Unable to find the book.";
        $message = "The book with id : ". $uid . " is not present on our database.";
        parent::__construct($message, 0, null);
    }
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    public function getHeaders()
    {
        return $this->headers;
    }
    public function getTitle()
    {
        return $this->title;
    }
}
