<?php


namespace App\MessageHandler;


use App\Message\ChangeNotifications;
use App\Middleware\MiddlewareCustom;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ChangeNotificationsHandler implements MessageHandlerInterface
{
    /** @var MiddlewareCustom  */
    private $middleWareCustom;


    public function __construct(MiddlewareCustom $middleWareCustom)
    {
        $this->middleWareCustom = $middleWareCustom;
    }

    public function __invoke(ChangeNotifications $message)
    {
        $output = date('d-m-y h:i:s') . " - Processing incoming notification of changes
        ";
        echo $output;
        $this->middleWareCustom->consumeChangeNotification($message->geMessageContent());
    }


}