<?php


namespace App\Service;


use App\Entity\ProductSystem;

use App\Repository\ProductSystemRepository;
use Doctrine\ORM\EntityManagerInterface;





class ProductSystemService
{
    /** @var ProductSystemRepository  */
    private $productSystemRepo;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ProductSystemRepository $productSystemRepo)
    {
        $this->entityManager = $entityManager;
        $this->productSystemRepo = $productSystemRepo;

    }

    /**
     * @param string $id
     * @return ProductSystem|null
     */
    public function getProductSystemById($id) {
        $productSystemById = $this->productSystemRepo->findOneBy(array('id' => $id));
        return $productSystemById;
    }

    /**
     * @param string $id
     * @return ProductSystem|null
     */
    public function getProductSystemBySku($sku) {
        $productSystemBySku = $this->productSystemRepo->findOneBy(array('sku' => $sku));
        return $productSystemBySku;
    }

    public function save($productSystem) : bool{

        return $this->productSystemRepo->save($productSystem);
    }


    /**
     * @param ProductSystem $product
     * @param string $name
     * @return bool
     */
    public function productContainsAttribute(ProductSystem $product, $name) : bool{
        foreach ($product->getProductAttributes() as $attribute){
            if($attribute->getName() == $name)
                return true;
        }
        return false;
    }


}