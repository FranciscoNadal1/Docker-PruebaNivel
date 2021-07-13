<?php


namespace App\Service;



class MappingService
{


    /** Divide el array, en caso de serlo, y mapea al formato de json común
     *
     * @param $json
     * @return string
     * @deprecated
     */
    function MapFromJSONFormat($json){

        $data = json_decode($json, true);

        if(is_array($data['Data'])){
            $listOfArticles = array();
            foreach($data['Data'] as $Articulo){
                $listOfArticles[] = $this->mapJSONToCommonJSON($Articulo);
            }
            return json_encode($listOfArticles);
        } else{
            return json_encode($this->mapJSONToCommonJSON($data['Data']));
        }
    }

    /** Divide el array, en caso de serlo y convierte a json antes de mapear los campos al formato común
     *
     * @param $xml
     * @return string
     * @deprecated
     */
    function MapFromXMLFormat($xml){

        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);
        $jsonDecode = json_decode($json);

        if(is_array($jsonDecode->Articulo)){
            $listOfArticles = array();
            foreach($jsonDecode->Articulo as $Articulo){
                $listOfArticles[] = $this->mapXMLToCommonJSON($Articulo);
            }
            return json_encode($listOfArticles);
        }else{
            return json_encode($this->mapXMLToCommonJSON($jsonDecode->Articulo));
        }
    }

    /** Da un formato legible al formato xlsx antes de mapear los campos al formato común
     *
     * @param $xlsxFile
     * @return string
     * @deprecated
     */
    function convertXLSXToJSON($xlsxFile){


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




        $listOfArticles = array();
        foreach($values as $Articulo){
            $listOfArticles[] = $this->mapXSLXFormatToCommonJSON($Articulo);
        }


        return json_encode($listOfArticles);

    }


    /** Mapea los campos al formato común de json
     *
     * @param $Articulo
     * @return array
     * @deprecated
     */
    function mapXSLXFormatToCommonJSON($Articulo){

        $object = array(
            'id'    => $this->mapFieldIfExists($Articulo, 'product_id'),
            'sku'    => $this->mapFieldIfExists($Articulo, 'sku_provider'),
            'description' => $this->mapFieldIfExists($Articulo, 'provider_short_description'),
            'ean13'  => $this->mapFieldIfExists($Articulo, 'ean'),
            'brandName'  => $this->mapFieldIfExists($Articulo, 'brand_supplier_name'),
            'width'  => $this->mapFieldIfExists($Articulo, 'width'),
            'height'  => $this->mapFieldIfExists($Articulo, 'height'),
            'length'  => $this->mapFieldIfExists($Articulo, 'length'),

            'widthPackaging'  => $this->mapFieldIfExists($Articulo, 'width_packaging'),
            'heightPackaging'  => $this->mapFieldIfExists($Articulo, 'height_packaging'),
            'lengthPackaging'  => $this->mapFieldIfExists($Articulo, 'length_packaging'),
            'weightPackaging'  => $this->mapFieldIfExists($Articulo, 'weight_packaging'),
            'cbm' => $this->mapFieldIfExists($Articulo, 'cbm'),
            'attributes' => null
        );


        $attributes = array();


        for($i=0;$i!=100;$i++){

            if(isset($Articulo["attribute_" . $i])){


                array_push($attributes, array(
                    'attributeName' => isset($Articulo["attribute_" . $i])? $Articulo["attribute_" . $i] : null,
                    'attributeValue' => isset($Articulo["value_" . $i])? $Articulo["value_" . $i] : null,
                ));
            }
        }

        $object['attributes'] = $attributes;


        return $object;

    }

    /** Devuelve nulo si el campo que se debe devolver no existe */
    function mapFieldIfExists($Articulo, $string){
        if(isset($Articulo[$string])){
            return $Articulo[$string];
        }
        return null;
    }

    /** Mapea los campos al formato común de json
     *
     * @param $Articulo
     * @return array
     * @deprecated
     */
    function mapXMLToCommonJSON($Articulo){

        $object = array(
            'id'    => isset($Articulo->Codigo)? $Articulo->Codigo : null,
            'description' => isset($Articulo->Descripcion)? $Articulo->Descripcion : null,
            'ean13'  => isset($Articulo->CodigoBarras)? $Articulo->CodigoBarras : null,
            'pvp' => isset($Articulo->Precio)? $Articulo->Precio : null,
            'priceRetail' => isset($Articulo->PrecioBase)? $Articulo->PrecioBase : null,
            'surtido' => isset($Articulo->Surtido)? $Articulo->Surtido : null,
            'stock' => isset($Articulo->StockReal)? $Articulo->StockReal : null,
            'stockCatalog' => isset($Articulo->StockTeorico)? $Articulo->StockTeorico : null,
            'stockAvailable' => isset($Articulo->StockDisponible)? $Articulo->StockDisponible : null,
            'vmd' => isset($Articulo->VMD)? $Articulo->VMD : null
        );

        return $object;
    }


    /** Mapea los campos al formato común de json
     * @param $Articulo
     * @return array
     * @deprecated
     * @deprecated
     */
    function mapJSONToCommonJSON($Articulo){
        $object = array(
            'id'    => isset($Articulo['Codigo'])? $Articulo['Codigo'] : null,
            'sku'    => isset($Articulo['Sku_Provider'])? $Articulo['Sku_Provider'] : null,
            'name' => isset($Articulo['Provider_Name'])? $Articulo['Provider_Name'] : null,
            'description' => isset($Articulo['Provider_Full_Description'])? $Articulo['Provider_Full_Description'] : null,
            'ean13'  => isset($Articulo['Ean'])? $Articulo['Ean'] : null,
            'categoryName'  => isset($Articulo['Category_Supplier_Name'])? $Articulo['Category_Supplier_Name'] : null,
            'brandName'  => isset($Articulo['Brand_Supplier_Name'])? $Articulo['Brand_Supplier_Name'] : null,
            'widthPackaging'  => isset($Articulo['Width_Packaging'])? $Articulo['Width_Packaging'] : null,
            'heightPackaging'  => isset($Articulo['Height_Packaging'])? $Articulo['Height_Packaging'] : null,
            'lengthPackaging'  => isset($Articulo['Length_Packaging'])? $Articulo['Length_Packaging'] : null,
            'weightPackaging'  => isset($Articulo['Weight_Packaging'])? $Articulo['Weight_Packaging'] : null,
            'productAttributes' => null,
            'productImages' => isset($Articulo['Images'])? $Articulo['Images'] : null
        );

        $attributes = array();

        if(isset($Articulo['Attributes']))
            foreach ($Articulo['Attributes'] as $attribute){
                array_push($attributes, array(
                    'attributeId' => isset($attribute['Attribute_ID'])? $attribute['Attribute_ID'] : null,
                    'attributeName' => isset($attribute['Attribute_Name'])? $attribute['Attribute_Name'] : null,
                    'attributeValue' => isset($attribute['Attribute_Value'])? $attribute['Attribute_Value'] : null,
                ));
            }
        $object['productAttributes'] = $attributes;





        return $object;
    }


}