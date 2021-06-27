<?php


namespace App\Controller\Api;


use App\Entity\ProductSystem;
use App\Middleware\MiddlewareCustom;
use App\Repository\ProductSystemRepository;
use App\Service\ConsumeService;
use App\Service\MappingService;
use App\Service\NotificationService;
use App\Service\ProductSystemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/consume/json", name="consume common json", methods={"POST"})
     */
    public function consumeCommonJson(Request $request, ConsumeService $consumeService, MiddlewareCustom $middlewareCustom): Response
    {


        $data = json_decode($request->getContent(), true);

        if(array_is_list($data)){
            foreach ($data as $productSystem){
                $notification = $consumeService->consumeCommonJson($productSystem);
                $middlewareCustom->sendChangeNotification($notification);


               // echo $consumeService->consumeCommonJson($productSystem);
                }
            }
        else{
            $notification = $consumeService->consumeCommonJson($data);
            $middlewareCustom->sendChangeNotification($notification);


          //  echo $consumeService->consumeCommonJson($data);
        }

        if( array_is_list($data))
            return new JsonResponse("succesfully updated list", Response::HTTP_NOT_ACCEPTABLE);
        else
            return new JsonResponse("succesfully updated", Response::HTTP_NOT_ACCEPTABLE);
        //      }

        return new JsonResponse("succesfully updated list", Response::HTTP_NOT_ACCEPTABLE);
        return new JsonResponse("succesfully updated", Response::HTTP_NOT_ACCEPTABLE);
    }








    /**
     * @Route("/update/json", name="update productSystem", methods={"POST"})
     */
    public function updateJson(Request $request, MappingService $mappingService, ConsumeService $consumeService, MiddlewareCustom $middlewareCustom): Response
    {

        $data = json_decode($request->getContent(), true);

        if($data['Result'] != 'OK'){
            return new JsonResponse("Api could not be retrieved", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $mappedJSON =  $mappingService->MapFromJSONFormat(json_encode($data));

        echo"
        ";
        echo $mappedJSON;
        echo"
        ";
        $middlewareCustom->sendOneOrMoreJsonToQueue($mappedJSON);



        return new JsonResponse("succesfully updated", Response::HTTP_NOT_ACCEPTABLE);
        //      }

    }

    /**
     * @Route("/update/xml", name="update productSystem XML format", methods={"POST"})
     */
    public function updateXML(Request $request, MappingService $mappingService, ProductSystemRepository $productSystemRepo): Response
    {

        print_r($mappingService->MapFromXMLFormat($request->getContent()));

        return new JsonResponse("succesfully updated", Response::HTTP_NOT_ACCEPTABLE);
        //      }

    }
}