<?php


namespace App\Service;


use App\Entity\ProductSystem;
use App\Middleware\MiddlewareCustom;
use App\Repository\ProductSystemRepository;
use Doctrine\ORM\EntityManagerInterface;use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



class ProductSystemService
{
    /** @var ProductSystemRepository  */
    private $productSystemRepo;

    public function __construct(EntityManagerInterface $entityManager, ProductSystemRepository $productSystemRepo)
    {
        $this->entityManager = $entityManager;
        $this->productSystemRepo = $productSystemRepo;

    }


//    /** @param string, ProductSystem, string, array */
//    public function checkChangesExistingProductsystem($fieldName, $objectCompared, $newJSON, &$changes, &$numberOfChanges) : void{
//
//        $getter="get" . ucfirst($fieldName);
//        $setter="set" . ucfirst($fieldName);
//
//
//        if(isset($newJSON[$fieldName]) && $objectCompared->{$getter}() != $newJSON[$fieldName]){
//
//            $arr = array('field' => $fieldName, 'oldValue' => $objectCompared->{$getter}(), 'newValue' => $newJSON[$fieldName]);
//            $changes[] = $arr;
//
//            $objectCompared->{$setter}($newJSON[$fieldName]);
//
//            $numberOfChanges++;
//        }
//}
//

//    public function sendChangesNotification(int $numberOfChanges, array $changes){
//
//        if($numberOfChanges != 0) {
//
//            $changes['fieldsChanged'] = $numberOfChanges;
//            $changes['changeDate'] = new \DateTime("now");
//
//            //TODO Send json to exterior system
//            $this->middlewareCustom->sendChangeNotification(json_encode($changes));
//        }
//    }
}