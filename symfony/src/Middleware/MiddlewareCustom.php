<?php


namespace App\Middleware;


use App\Message\ChangeNotifications;
use App\Message\DataUpdates;
use App\Repository\ProductSystemRepository;
use App\Service\ConsumeService;
use App\Service\ProductSystemService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;

class MiddlewareCustom
{
    /** @var MessageBusInterface  */
    private $messageBusInterface;



    public function __construct(MessageBusInterface $messageBusInterface, ConsumeService $consumeService)
    {
        $this->messageBusInterface = $messageBusInterface;
        $this->consumeService = $consumeService;
    }




    public function sendChangeNotification($jsonString){

        $this->messageBusInterface->dispatch(
            (new Envelope(new ChangeNotifications($jsonString)))->with(new SerializerStamp([
                'groups' => ['ChangeNotifications'],
            ]))
        );

    }

    public function sendDataUpdates($jsonString)
    {
        $this->messageBusInterface->dispatch(
            (new Envelope(new DataUpdates($jsonString)))->with(new SerializerStamp([
                'groups' => ['DataUpdates'],
            ]))
        );

    }



    public function consumeChangeNotification($message){

        //TODO MANAGE NOTIFICATIONS
        echo(">_<");
    //    print_r($message);
    }

    public function consumeDataUpdates($message){
        try{

        /** @var string $notification */
        $notification = $this->consumeService->consumeCommonJson((array)json_decode($message));

        if($notification != null){
                echo "There was a modification
                ";

            $this->sendChangeNotification($notification);
        }


        }catch(\Exception $e){

        }
    }

    public function sendOneOrMoreJsonToQueue($json){

        $mappedJSON =  $json;

        $data = json_decode($mappedJSON, true);

        if(array_is_list($data)){
            foreach ($data as $productSystem){
               $this->sendDataUpdates(json_encode($productSystem));

            }
        }
        else{
           $this->sendDataUpdates(json_encode($data));

        }
    }

//    public function sendChangesNotification(int $numberOfChanges, array $changes){
//
//        if($numberOfChanges != 0) {
//
//            $changes['fieldsChanged'] = $numberOfChanges;
//            $changes['changeDate'] = new \DateTime("now");
//
//            $this->middlewareCustom->sendChangeNotification(json_encode($changes));
//        }
//    }
}