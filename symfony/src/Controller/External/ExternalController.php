<?php


namespace App\Controller\External;


use App\Entity\Notification;
use App\Entity\UpdatedField;
use App\Repository\NotificationRepository;
use App\Repository\ProductSystemRepository;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExternalController extends AbstractController
{

    /** var NotificationRepository */
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }



    /**
     * @Route("/external/receiveNotification", name="Receive notification", methods={"POST"})
     * @return Response
     */
    public function receiveNotification(Request $request): Response
    {


        $data = json_decode($request->getContent(), true);


//        foreach ($data as $notificationJson){
//
//            /** var Notification */
//            $notification = new Notification($notificationJson['type'],$notificationJson['description']);
//            $notification->setRelatedProductId($notificationJson['relatedProductId']);
//    /*
//            $updatedField = new UpdatedField($notification, "field1","oldValue", "newValue");
//            $notification->addFields($updatedField);
//    */
//            $this->notificationRepository->save($notification);
//        }
        $this->saveNotification($data);

            return new JsonResponse("Notification received", Response::HTTP_OK);
    }


    /**
     * @Route("/external/listNotifications", name="List notifications", methods={"GET"})
     * @return Response
     */
    public function listNotifications(NotificationService $notificationService)
    {

        return $this->render('external/listNotifications.html.twig', array(

            'Notifications' => $notificationService->getAllNotifications()
            /*
'UtilsCommonVars' => $utils->getVars()
*/
        ));
    }


    public function saveNotification($message){

        /** var Notification */
        if(!is_array($message))
            $message = json_decode($message);


        if(is_object($message)){
            $notification = new Notification($message->type,$message->description);
            $notification->setRelatedProductId($message->relatedProductId);
            foreach($message->fields as  $field){
                //print_r($field);

                    $updatedField = new UpdatedField($notification, $field->field, $field->oldValue, $field->newValue);
                    $notification->addFields($updatedField);

            }
            $this->notificationRepository->save($notification);
        }
            else{

                $message = json_decode($message);
        $notification = new Notification($message['type'],$message['description']);
        $notification->setRelatedProductId($message['relatedProductId']);
        /*
                $updatedField = new UpdatedField($notification, "field1","oldValue", "newValue");
                $notification->addFields($updatedField);
        */
        $this->notificationRepository->save($notification);
            }

    }
}