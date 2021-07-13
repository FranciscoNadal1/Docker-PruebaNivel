<?php


namespace App\Entity\Providers;


use App\Entity\ProductAttribute;
use App\Entity\ProductSystem;
use SimpleXLSX;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class Provider_XSLX extends ProductSystem implements ProductProviderInterface
{

    /**
     * Provider_JSON constructor.
     * @param  $xlsxObject
     */
    public function __construct( $xlsxObject)
    {

        parent::__construct();


        if(isset($jsonToConvert['product_id']))
            $this->setId($xlsxObject['product_id']);




        if(isset($xlsxObject['product_id']))
            $this->setId($xlsxObject['product_id']);

        if(isset($xlsxObject['sku_provider']))
            $this->setSku($xlsxObject['sku_provider']);

        if(isset($xlsxObject['ean']))
            $this->setEan13($xlsxObject['ean']);

        if(isset($xlsxObject['provider_short_description']))
            $this->setDescription($xlsxObject['provider_short_description']);

        if(isset($xlsxObject['category_supplier_name']))
            $this->setCategoryName($xlsxObject['category_supplier_name']);

        if(isset($xlsxObject['brand_supplier_name']))
            $this->setBrandName($xlsxObject['brand_supplier_name']);

        if(isset($xlsxObject['height']))
            $this->setHeight(floatval($xlsxObject['height']));
        if(isset($xlsxObject['length']))
            $this->setLength(floatval($xlsxObject['length']));
        if(isset($xlsxObject['width']))
            $this->setWidth(floatval($xlsxObject['width']));
        if(isset($xlsxObject['weight']))
            $this->setWeight(floatval($xlsxObject['weight']));


        if(isset($xlsxObject['width_packaging']))
            $this->setWidthPackaging($xlsxObject['width_packaging']);

        if(isset($xlsxObject['height_packaging']))
            $this->setHeightPackaging($xlsxObject['height_packaging']);

        if(isset($xlsxObject['length_packaging']))
            $this->setLengthPackaging($xlsxObject['length_packaging']);



        $attributes = array();

        for($i=0;$i!=100;$i++){

            if(isset($xlsxObject["attribute_" . $i])){


                $attributeId = isset($xlsxObject['Attribute_ID']) ? $xlsxObject['Attribute_ID'] : null;
                $attributeName = isset($xlsxObject["attribute_" . $i])? $xlsxObject["attribute_" . $i] : null;
                $attributeValue = isset($xlsxObject["value_" . $i])? $xlsxObject["value_" . $i] : null;


//                echo $xlsxObject["attribute_" . $i];
//                array_push($attributes, array(
//                    'attributeName' => $attributeName,
//                    'attributeValue' => $attributeValue,
//                ));

                if(isset($attributeName)){
                    $attributeObject = new ProductAttribute($this, $attributeName, $attributeValue, $attributeId);
                    array_push($attributes, $attributeObject);
                }
            }
        }

        $this->setProductAttributes($attributes);


    }


    public function normalizeToJson(): string
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($this, 'json');

        return $jsonContent;
    }



    public static function  XLSXToPlainArrayOfValues($xlsxFile) : array{


        $object = array();
        $flag = true;
        $xlsxMapper = array();

        $values = array();

        foreach($xlsxFile->rows() as $xlsxSingleData) {

            if ($flag) {
                foreach ($xlsxSingleData as $cell) {
                    $xlsxMapper[] = $cell;
                }
            }
            if($flag){
                $flag = false;
                continue;
            }
            if(!$flag){
                foreach($xlsxSingleData as $cell){
                }

                $position = 0;
                foreach($xlsxMapper as $mapField){
                    $object[$mapField] = isset($xlsxSingleData[$position])? $xlsxSingleData[$position] : null;
                    $position++;
                }
            }
            $values[] = $object;
        }




//        $listOfArticles = array();
//        print_r($values);
//        foreach($values as $Articulo){
// // TODO falta esto
//            //           $listOfArticles[] = $this->mapXSLXFormatToCommonJSON($Articulo);
//        }


        return $values;
    }

}