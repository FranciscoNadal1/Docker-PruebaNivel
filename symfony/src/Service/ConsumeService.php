<?php


namespace App\Service;


use App\Entity\ProductSystem;
use App\Middleware\MiddlewareCustom;
use App\Repository\ProductSystemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConsumeService
{

 //   /** @var ProductSystemRepository  */
 //   private $productSystemRepo;

 //   /** @var ProductSystemService  */
 //   private $productSystemService;


    public function __construct(ProductSystemRepository $productSystemRepo)
    {
        $this->productSystemRepo = $productSystemRepo;
    }

    public function consumeCommonJson(array $json) : ?string
    {
        /** @var int $numberOfChanges */
        $numberOfChanges = 0;
        /** @var bool $isNewProduct */
        $isNewProduct = true;

        $changes = array();
        if(isset($json['id'])){

            /** @var ProductSystem|null $user */
            $productSystemById = $this->productSystemRepo->findOneBy(array('id' => $json['id']));

        }

        if(isset($json['sku'])){
            /** @var ProductSystem|null $user */
            $productSystemBySku = $this->productSystemRepo->findOneBy(array('sku' => $json['sku']));
            if($productSystemBySku != null)
                $isNewProduct = false;
        }

        if(isset($json['id']) && $productSystemById != null){
            $newProductSystem = $productSystemById;
            $isNewProduct = false;
        }
        else if(isset($json['sku']) && $productSystemBySku != null){
            $newProductSystem = $productSystemBySku;
        }else{
            $newProductSystem = new ProductSystem();
        }

        if(isset($json['id']) &&  $productSystemById == null){
            $newProductSystem->setId($json['id']);
        }

        if(isset($json['id']) && isset($json['sku']) && $newProductSystem->getSku() != $json['sku']){

            $arr = array('field' => 'sku', 'oldValue' => $newProductSystem->getSku(), 'newValue' => $json['sku']);
            $changes[] = $arr;
            $numberOfChanges++;

            $newProductSystem->setSku($json['sku']);
        }


        // TODO ¿VMD?
        $fieldsToMap = array('description', 'name', 'brandName', 'stock','stockCatalog', 'stockAvailable',
            'weightPackaging', 'lengthPackaging','heightPackaging', 'widthPackaging', 'categoryName', 'ean13',
            'stockAvailable', 'pvp', 'priceRetail', 'height', 'width', 'length');
        foreach($fieldsToMap as $field)    {
            $this->checkChangesExistingProductSystem($field, $newProductSystem, $json, $changes, $numberOfChanges);
        }

        $changesJson = array("id" => $newProductSystem->getId(), "modifications" => $changes, "fieldsChanged" => null, "changeDate" => null);


        $this->productSystemRepo->save($newProductSystem);

        if(!$isNewProduct && $numberOfChanges != 0)
            return $this->sendChangesNotification($numberOfChanges, $changesJson);

        return null;
    }


    /** @param string, ProductSystem, string, array */

    public function checkChangesExistingProductSystem($fieldName, $objectCompared, $newJSON, &$changes, &$numberOfChanges) : void{

        $getter="get" . ucfirst($fieldName);
        $setter="set" . ucfirst($fieldName);


        if(isset($newJSON[$fieldName]) && $objectCompared->{$getter}() != $newJSON[$fieldName]){

            $arr = array('field' => $fieldName, 'oldValue' => $objectCompared->{$getter}(), 'newValue' => $newJSON[$fieldName]);
            $changes[] = $arr;

            $objectCompared->{$setter}($newJSON[$fieldName]);

            $numberOfChanges++;
        }

    }

    public function sendChangesNotification(int $numberOfChanges, array $changes){

        if($numberOfChanges != 0) {

            $changes['fieldsChanged'] = $numberOfChanges;
            $changes['changeDate'] = new \DateTime("now");

            //TODO Send json to exterior system

           return json_encode($changes);
         //   $this->middlewareCustom->sendChangeNotification(json_encode($changes));
        }
    }


}