<?php


namespace App\Controller;


use App\Controller\External\ExternalController;
use App\Middleware\MiddlewareCustom;
use App\Service\ConsumeService;
use App\Service\MappingService;
use App\Service\NotificationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchApiController
{


    /** @var HttpClientInterface  */
    private $client;

    /** @var MiddlewareCustom  */
    private $middlewareCustom;

    /** @var MappingService  */
    private $mappingService;

    public function __construct(HttpClientInterface $client, MiddlewareCustom $middlewareCustom, MappingService $mappingService)
    {
        $this->client = $client;
        $this->middlewareCustom = $middlewareCustom;
        $this->mappingService = $mappingService;
    }

    /**
     * @return string
     */
    public function fetchApi($url) : string{

        try{
    /** @var Response $response */
            $response = $this->client->request("GET", $url);
            return $response->getContent();
        }catch(\Throwable $t){
            $notification = NotificationService::newErrorFetchRemoteApi($t->getMessage());
            $this->middlewareCustom->sendChangeNotification($notification);
        }

    }

    public function consumFromFetchApi($content){
        try{
            $mappedJSON =  $this->mappingService->MapFromJSONFormat($content);
            $this->middlewareCustom->sendOneOrMoreJsonToQueue($mappedJSON);

        }
        catch(\Throwable $t){
            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newErrorNotificationCouldNotReadJson($t->getMessage()));
        }

    }

    public function consumeAndFetch($url){
        $content = $this->fetchApi($url);

        $this->consumFromFetchApi($content);
    }

}