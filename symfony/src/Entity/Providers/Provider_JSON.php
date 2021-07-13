<?php


namespace App\Entity\Providers;


use App\Entity\ProductAttribute;
use App\Entity\ProductSystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class Provider_JSON extends ProductSystem implements ProductProviderInterface
{

    /**
     * Provider_JSON constructor.
     * @param array $jsonToConvert
     */
    public function __construct(array $jsonToConvert)
    {
        parent::__construct();


        if(isset($jsonToConvert['Codigo']))
            $this->setId($jsonToConvert['Codigo']);

        if(isset($jsonToConvert['Sku_Provider']))
            $this->setSku($jsonToConvert['Sku_Provider']);

        if(isset($jsonToConvert['Provider_Name']))
            $this->setName($jsonToConvert['Provider_Name']);

        if(isset($jsonToConvert['Provider_Full_Description']))
            $this->setDescription($jsonToConvert['Provider_Full_Description']);

        if(isset($jsonToConvert['Ean']))
            $this->setEan13($jsonToConvert['Ean']);

        if(isset($jsonToConvert['Category_Supplier_Name']))
            $this->setCategoryName($jsonToConvert['Category_Supplier_Name']);

        if(isset($jsonToConvert['Brand_Supplier_Name']))
            $this->setBrandName($jsonToConvert['Brand_Supplier_Name']);

        if(isset($jsonToConvert['Width_Packaging']))
            $this->setWidthPackaging($jsonToConvert['Width_Packaging']);

        if(isset($jsonToConvert['Height_Packaging']))
            $this->setHeightPackaging($jsonToConvert['Height_Packaging']);

        if(isset($jsonToConvert['Length_Packaging']))
            $this->setLengthPackaging($jsonToConvert['Length_Packaging']);

        if(isset($jsonToConvert['Weight_Packaging']))
            $this->setWeightPackaging($jsonToConvert['Weight_Packaging']);

        if(isset($jsonToConvert['Images']))
            $this->setProductImages($jsonToConvert['Images']);


        $attributes = array();
        if(isset($jsonToConvert['Attributes'])) {
            foreach ($jsonToConvert['Attributes'] as $attribute) {
                $attributeId = isset($attribute['Attribute_ID']) ? $attribute['Attribute_ID'] : null;
                $attributeName = isset($attribute['Attribute_Name']) ? $attribute['Attribute_Name'] : null;
                $attributeValue = isset($attribute['Attribute_Value']) ? $attribute['Attribute_Value'] : null;

                $attributeObject = new ProductAttribute($this, $attributeName, $attributeValue, $attributeId);
                array_push($attributes, $attributeObject);
            }

            $this->setProductAttributes($attributes);
        }

    }


    public function normalizeToJson(): string
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($this, 'json');

        return $jsonContent;
    }

}