<?php


namespace App\Entity\Providers;


use App\Entity\ProductAttribute;
use App\Entity\ProductSystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class Provider_XML extends ProductSystem implements ProductProviderInterface
{

    /**
     * Provider_JSON constructor.
     * @param  $xmlToConvert
     */
    public function __construct($xmlToConvert)
    {
        parent::__construct();





        if(isset($xmlToConvert->Codigo))
            $this->setId($xmlToConvert->Codigo);

        if(isset($xmlToConvert->Descripcion))
            $this->setDescription($xmlToConvert->Descripcion);

        if(isset($xmlToConvert->CodigoBarras))
            $this->setEan13($xmlToConvert->CodigoBarras);

        if(isset($xmlToConvert->Precio))
            $this->setPvp($xmlToConvert->Precio);

        if(isset($xmlToConvert->PrecioBase))
            $this->setPriceRetail($xmlToConvert->PrecioBase);

        if(isset($xmlToConvert->StockReal))
            $this->setStock($xmlToConvert->StockReal);

        if(isset($xmlToConvert->StockTeorico))
            $this->setStockCatalog($xmlToConvert->StockTeorico);

        if(isset($xmlToConvert->StockDisponible))
            $this->setStockToShow($xmlToConvert->StockDisponible);



    }


    public function normalizeToJson(): string
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($this, 'json');

        return $jsonContent;
    }

    public static function separateXMLIntoArticles($xml){

        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);
        $jsonDecode = json_decode($json);

        return $jsonDecode;
    }
}