<?php
namespace AppBundle\Exception;

class NotEnabledException extends \Exception implements ExceptionInterface
{
    private $statusCode = 329;
    private $headers;
    private $title;

    public function __construct($book)
    {
        $this->headers = array(
            'uid' => $book->getUid()
        );
        $this->title = "The book is not enabled";
        $message = "The book with id : ". $book->getUid() . " is present on our database but you haven't confirm it.";
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
