<?php


namespace App\Middleware;


use App\Controller\External\ExternalController;
use App\Controller\FTPController;
use App\Message\ChangeNotifications;
use App\Message\DataUpdates;
use App\Service\ConsumeService;
use App\Service\NotificationService;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MiddlewareCustom
{
    /** @var MessageBusInterface  */
    private $messageBusInterface;

    /** @var ExternalController  */
    private $externalController;

    /** @var HttpClientInterface  */
    private $client;

    /** @var ConsumeService     */
    private $consumeService;


    public function __construct(MessageBusInterface $messageBusInterface, ConsumeService $consumeService, HttpClientInterface $client, ExternalController $externalController)
    {
        $this->messageBusInterface = $messageBusInterface;
        $this->consumeService = $consumeService;
        $this->client = $client;
        $this->externalController = $externalController;
    }


    /** Se envía la notificacion a la cola para tratarla de forma asíncrona
     *
     *
     * @param string $jsonString
     */
    public function sendChangeNotification($jsonString)
    {

        if ($jsonString != null) {
            $this->messageBusInterface->dispatch(
                (new Envelope(new ChangeNotifications($jsonString)))->with(new SerializerStamp([
                    'groups' => ['ChangeNotifications'],
                ]))
            );
        }
    }

    /** Se envía el Json en formato común para ser consumido y aplicado en la base de datos
     * posteriormente de forma asíncrona.
     *
     *
     * @param string $jsonString
     */
    public function sendDataUpdates($jsonString)
    {
        $this->messageBusInterface->dispatch(
            (new Envelope(new DataUpdates($jsonString)))->with(new SerializerStamp([
                'groups' => ['DataUpdates'],
            ]))
        );

    }


    /** Esta clase debería enviar a un servidor externo un json con la notificación, para que
     * el servidor pueda procesarla por su cuenta. Debido a que se envía un JSON, idealmente
     * este servidor debería contar con tecnología NOSQL.
     *
     * Para la prueba conectamos directamente con la clase "externalController", ya que el
     * funcionamiento es muy parecido.
     *
     * @param string $message
     */
    public function consumeChangeNotification($message){

//        try {
//            $this->client->request("POST", "http://localhost/external/receiveNotification",
//                array(
//                    'headers' => [
//                        'Content-Type' => 'application/json'
//                    ], // iterable|string[]|string[][]
//                    'body' => $message
//                ));
//        } catch (TransportExceptionInterface $e) {
//            echo "ups";
//            echo $e;
//
//        }
        $this->externalController->saveNotification($message);


    }

    /** Recibimos un json plano con la notificacion
     *
     *
     * @param  $message
     */
    public function consumeDataUpdates($message){
        try{

        /** @var string $notification */
        $notification = $this->consumeService->consumeCommonJson((array)json_decode($message));

        if($notification != null){
            $this->sendChangeNotification($notification);
        }

        }catch(\Throwable $t){

        FTPController::uploadFailedJson($message);
        $this->sendChangeNotification(NotificationService::newErrorConsumingCommonJson($t->getMessage()));

        }
    }

    /** Recibe el json en el formato común y como string o array. Descomponemos en string si es un listado
     * y enviamos individualmente a la cola todos los json que posteriormente va a consumir y aplicar cambios.
     *
     * @param $json
     */
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


}