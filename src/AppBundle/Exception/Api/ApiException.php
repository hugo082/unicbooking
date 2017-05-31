<?php

namespace AppBundle\Exception\Api;

use Throwable;

class ApiException extends \Exception
{
    /**
     * @var string
     */
    protected $title;

    public function __construct(string $title, string $message, int $code, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->title = $title;
    }

    public function encode(): array {
        return array(
            "code" => $this->code,
            "title" => $this->title,
            "message" => $this->message
        );
    }
}