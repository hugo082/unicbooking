<?php
namespace AppBundle\Exception;

class NotAcceptedException extends \Exception implements ExceptionInterface
{
    private $statusCode = 331;
    private $headers;
    private $title;

    public function __construct($book)
    {
        $this->headers = array(
            'id' => $book->getid()
        );
        $this->title = "Book has not been accepted";
        $message = "The book with id : ". $book->getid() . " is present on our database but it was not accepted by an admin..";
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
