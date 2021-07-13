<?php


namespace App\Service;


use App\Entity\ProductAttribute;
use App\Entity\ProductSystem;
use App\Entity\UpdatedField;
use App\Repository\ProductAttributeRepository;
use App\Repository\ProductSystemRepository;

class ConsumeService
{


    /**
     * @var ProductSystemRepository
     */
    private $productSystemRepo;

    /**
     * @var ProductSystemService
     */
    private $productSystemService;

    public function __construct(ProductSystemRepository $productSystemRepo, ProductSystemService $productSystemService)
    {
        $this->productSystemRepo = $productSystemRepo;
        $this->productSystemService = $productSystemService;
    }


    /** Esta función es la que se encarga, una vez recibido el array con el json común, 
     * de parsear el json a una entidad que luego se persiste en la base de datos, además
     * de examinar si ha habido cambios y generar un reporte que va a devolver con formato string,
     * de forma que una cola recibirá la notificación.
     *
     *
     * @param $json
     * @return string|null
     */
    public function consumeCommonJson($json) : ?string    {
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///                     Carga los objetos si ya existen
///
        /** @var int $numberOfChanges */
        $numberOfChanges = 0;

        /** @var bool $isNewProduct */
        $isNewProduct = true;

        $changes = array();
        $notification = null;



        if(isset($json['id']) and $json['id'] != null){

            //TODO Cambiar a service
            /** @var ProductSystem|null $productSystemById */
            $productSystemById = $this->productSystemService->getProductSystemById($json['id']);

        }

        if(isset($json['sku']) and $json['sku'] != null){

            //TODO Cambiar a service
            /** @var ProductSystem|null $productSystemBySku */
            $productSystemBySku = $this->productSystemService->getProductSystemBySku($json['sku']);

            if($productSystemBySku != null){
                $isNewProduct = false;
            }

        }

        if(isset($json['id']) && $productSystemById != null){
            $newProductSystem = $productSystemById;
            $isNewProduct = false;


        }
        else if(isset($json['sku']) && $productSystemBySku != null){
            $newProductSystem = $productSystemBySku;
            $isNewProduct = false;


        }else{
            $newProductSystem = new ProductSystem();
            if(isset($json['id'])){
                $newProductSystem->setId($json['id']);
            }
            if(isset($json['sku'])){
                $newProductSystem->setSku($json['sku']);
            }
            $isNewProduct = true;
        }

        if(isset($json['id']) &&  $productSystemById == null){
            $newProductSystem->setId($json['id']);
        }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Si dispones del Id, puedes cambiar el sku
///
        if(isset($json['id']) && isset($json['sku']) && $newProductSystem->getSku() != $json['sku']){

            $arr = array('field' => 'sku', 'oldValue' => $newProductSystem->getSku(), 'newValue' => $json['sku']);
            $changes[] = $arr;
            $numberOfChanges++;

            $newProductSystem->setSku($json['sku']);

        }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Mapea un todos los caracteres json a un objeto ProductSystem, se especifica que campos con el
///  listado y se mapean dinamicamente, además se encarga de guardar en una variable los posibles cambios,
///  para reportarlos luego a un servidor externo
///

        $fieldsToMap = array('description', 'name', 'brandName', 'stock','stockCatalog', 'stockAvailable',
            'weightPackaging', 'lengthPackaging','heightPackaging', 'widthPackaging', 'categoryName', 'ean13',
            'stockAvailable', 'pvp', 'priceRetail', 'height', 'width', 'length', 'productImages');
        foreach($fieldsToMap as $field)    {
            $this->checkChangesExistingProductSystem($field, $newProductSystem, $json, $changes, $numberOfChanges);
        }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Mapea los atributos y se encarga de examinar si ha habido cambios y los guarda en una variable para reportar luego
///


        if(isset($json['productAttributes']) and !empty($json['productAttributes'])){
                foreach($json['productAttributes'] as $attribute){


                    $attributeName = $attribute->name;
                    $attributeValue = $attribute->value;

                    if(isset($attribute->name))
                        if(!$this->productSystemService->productContainsAttribute($newProductSystem, $attributeName)){

                            $newProductSystem->addProductAttribute(new ProductAttribute($newProductSystem,$attributeName,$attributeValue,""));
                        }
                        else{

                            if($newProductSystem->setProductAttributeValueIfDifferent($attributeName, $attributeValue)){
                                $arr = array('field' => $attributeName, 'oldValue' => $attributeValue, 'newValue' => $attributeValue);
                                $changes[] = $arr;
                                $numberOfChanges++;

                            }
                        }
                }
            }

        $this->productSystemService->save($newProductSystem);

        if(!$isNewProduct && $numberOfChanges != 0){


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Se genera el reporte de cambios, si existen, y se devuelve a la clase anterior,
///  que luego enviara la notificación a la cola
///
            $notification = NotificationService::newNotificationProductUpdate($newProductSystem->getId());
            foreach($changes as $change){
                $notification->addFields(new UpdatedField($notification, $change['field'], $change['oldValue'], $change['newValue']));
            }
            return (string)$notification;


        }else if($isNewProduct){
            return (string)NotificationService::newNotificationNewProduct($newProductSystem->getId());
        }

        return null;
    }


    /** Esta función se encarga de comparar los campos de ProductSystem, 
     * para examinar si ha habido cambios, y 
     * además generar el array que servirá luego para generar el reporte
     * 
     * 
     * @param string $fieldName
     * @param ProductSystem $objectCompared
     * @param $newJSON
     * @param array $changes
     * @param int $numberOfChanges
     */
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
}