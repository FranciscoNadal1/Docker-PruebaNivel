<?php


namespace App\MessageHandler;



use App\Message\DataUpdates;
use App\Middleware\MiddlewareCustom;
use App\Service\ProductSystemService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DataUpdatesHandler implements MessageHandlerInterface
{
    /** @var MiddlewareCustom  */
    private $middleWareCustom;


    public function __construct(MiddlewareCustom $middleWareCustom)
    {
        $this->middleWareCustom = $middleWareCustom;
    }


    public function __invoke(DataUpdates $message)
    {

//        $output = "
//        ".date('d-m-y h:i:s') . " - Processing incoming data update";
//
//        echo $output;

        $this->middleWareCustom->consumeDataUpdates($message->geMessageContent());
 //       echo $message->geMessageContent();
    }


}