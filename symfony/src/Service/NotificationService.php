<?php


namespace App\Service;


use App\Entity\Notification;
use App\Entity\UpdatedField;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{

    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var NotificationRepository  */
    private $notificationRepository;


    public function __construct(EntityManagerInterface $entityManager, NotificationRepository $notificationRepository)
    {
        $this->entityManager = $entityManager;
        $this->notificationRepository = $notificationRepository;
    }


    public function getAllNotifications(){
        return $this->notificationRepository->findBy(array(), array('createdAt' => 'DESC'));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///             RETURN      PREMADE     NOTIFICATIONS
///
    public static function newNotificationProductUpdate($relatedProductId) : Notification{

        $notification = new Notification("UpdateProductData","Existing product has been updated");
        $notification->setRelatedProductId($relatedProductId);

        return $notification;
    }

    public static function newNotificationNewProduct($relatedProductId) : Notification{

        $notification = new Notification("NewProduct","New product was created");
        $notification->setRelatedProductId($relatedProductId);

        return $notification;
    }


    public static function newNotificationErrorCouldNotParse($payload) : Notification{

        $notification = new Notification("ParsingError","Could not parse payload : " . $payload);

        return $notification;
    }

    public static function newNotificationProcessedFile($processedFileName) : Notification{

        $notification = new Notification("ProcessedFile","The file with name : " . $processedFileName . " was processed");
        return $notification;
    }


    public static function newErrorFetchRemoteApi($errorDetails) : Notification{

        $notification = new Notification("Error","Could not access remote api : " . $errorDetails);
        return $notification;
    }

    public static function newErrorConsumingCommonJson($errorDetails) : Notification{

        $notification = new Notification("Error","Json could not be consumed : " . $errorDetails);
        return $notification;
    }

    public static function newErrorNotificationCouldNotReadJson(string $errorDetails) : Notification{

        $notification = new Notification("Error","Json file could not be processed: + " . $errorDetails);
        return $notification;
    }

    public static function newErrorNotificationCouldNotReadXlsx(string $errorDetails) : Notification{

        $notification = new Notification("Error","Xslx file could not be processed : " . $errorDetails);
        return $notification;
    }

    public static function newErrorNotificationCouldNotReadXml(string $errorDetails) : Notification{

        $notification = new Notification("Error","Xml file could not be processed : " . $errorDetails);
        return $notification;
    }


    public static function newErrorNotificationFtpErrorConnection(string $errorDetails) : Notification{

        $notification = new Notification("Error","Could not access FTP connection : " . $errorDetails);
        return $notification;
    }

    public static function newUpdatedField(Notification $notification, $field, $oldValue, $newValue) : UpdatedField{

        $updatedField = new UpdatedField($notification, $field,$oldValue, $newValue);
        return $updatedField;
    }
}