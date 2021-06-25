<?php


namespace App\Controller\Api;


use App\Entity\ProductSystem;
use App\Repository\ProductSystemRepository;
use App\Service\MappingService;
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
    public function consumeCommonJson(Request $request, ProductSystemRepository $productSystemRepo, ProductSystemService $productSystemService): Response
    {

        $data = json_decode($request->getContent(), true);

        if(array_is_list($data)){
            foreach ($data as $productSystem){
                $productSystemService->consumeCommonJson($productSystem);
                }
            }
        else{
            $productSystemService->consumeCommonJson($data);
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
    public function updateJson(Request $request, ProductSystemRepository $productSystemRepo): Response
    {

        $data = json_decode($request->getContent(), true);

        if($data['Result'] != 'OK'){
            return new JsonResponse("Api could not be retrieved", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        foreach ($data['Data'] as $productSystem){

            $newProductSystem = new ProductSystem();

            $newProductSystem->setSku($productSystem['Sku_Provider']);
            $newProductSystem->setEan13($productSystem['Ean']);
            $newProductSystem->setDescription($productSystem['Provider_Full_Description']);
            $newProductSystem->setName($productSystem['Provider_Name']);
            $newProductSystem->setBrandName($productSystem['Brand_Supplier_Name']);
            $newProductSystem->setCategoryName($productSystem['Category_Supplier_Name']);
            $newProductSystem->setWidthPackaging($productSystem['Width_Packaging']);
            $newProductSystem->setHeightPackaging($productSystem['Height_Packaging']);
            $newProductSystem->setLengthPackaging($productSystem['Length_Packaging']);
            $newProductSystem->setWeightPackaging($productSystem['Weight_Packaging']);

            if(!$productSystemRepo->save($newProductSystem))
                echo "nop</br>";


        }

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