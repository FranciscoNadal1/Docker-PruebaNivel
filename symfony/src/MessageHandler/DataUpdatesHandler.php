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

    /** Consume el mensaje y envia el json a procesar
     */
    public function __invoke(DataUpdates $message)
    {
        $this->middleWareCustom->consumeDataUpdates($message->geMessageContent());
    }


}