<?php
namespace AppBundle\Exception;

class AlreadyEditedException extends \Exception implements ExceptionInterface
{
    private $statusCode = 327;
    private $headers;
    private $title;

    public function __construct($book)
    {
        $this->headers = array();
        $this->title = "Unable to edit this book.";
        $message = "The book (". $book->getid() . ") is still waiting to accept the last change.";
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
