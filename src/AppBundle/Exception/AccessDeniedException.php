<?php
namespace AppBundle\Exception;

class AccessDeniedException extends \Exception implements ExceptionInterface
{
    private $statusCode = 330;
    private $headers;
    private $title;

    public function __construct($book)
    {
        $this->headers = array();
        $this->title = "Access denied";
        $message = "You do not have the right to access this page.";
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
