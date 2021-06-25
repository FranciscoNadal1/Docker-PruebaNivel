<?php


namespace App\Message;


class DataUpdates
{

    /** @var string */
    private $messageContent;

    public function __construct(string $messageContent)
    {
        $this->messageContent = $messageContent;
    }

    /**
     * @return string
     */
    public function geMessageContent(): string
   {
       return $this->messageContent;
   }

}
