<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductAttributeRepository;
use App\Repository\ProductLang;
use App\Entity\ProductSystemInterface;
use App\Repository\ProductSystemRepository;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ProductSystemRepository::class)
 */
class ProductSystem implements ProductSystemInterface
{
    //* @ORM\GeneratedValue(strategy="UUID")
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     */
    private $id;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $sku;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $name;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $categoryName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $stock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $stockCatalog;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $stockAvailable;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $pvp;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $priceRetail;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $ean13;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $eanVirtual;

    //TODO Array
    private $eans;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $brandName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $partNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $collection;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $priceCatalog;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $priceWholesale;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $weight;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $height;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $width;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $length;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $weightPackaging;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $lengthPackaging;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $heightPackaging;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $widthPackaging;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $weightMaster;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $lengthMaster;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $heightMaster;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $widthMaster;

    //TODO Array
    private $productLangsSupplier;

    //TODO Array
    /**
     * @ORM\Column(name="productImages", type="array", nullable=true)
     */
    private $productImages;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $tax;



    /**
     * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="relatedProductSystem", cascade={"persist"})
     * @ORM\JoinColumn(name="attribute_id", onDelete="CASCADE", nullable=true)
     */
    private $productAttributes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $unitBox;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $unitPalet;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $assortment;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $minSalesUnit;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * ProductSystem constructor.
     */
    public function __construct()
    {
        $this->id= Uuid::v4();
        $this->productAttributes = array();
    }

    /**
     * @var array
     */

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setEan13(?string $ean13)
    {
        $this->ean13 = $ean13;
    }

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEanVirtual(?string $eanVirtual)
    {
        $this->eanVirtual = $eanVirtual;
    }

    public function getEanVirtual(): ?string
    {
        return $this->eanVirtual;
    }

    public function setProductEans(array $eans)
    {
        $this->eans = $eans;
    }

    public function getProductEans(): array
    {
        // TODO ups
     //   return $this->eans;
      //  $arr2 = str_split($str, 3);
        return explode(",", $this->getEan13());
        //return str_split($this->getEan13(), ",");
    }

    public function setStock(int $stock)
    {
        $this->stock = $stock;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStockCatalog(int $stockCatalog)
    {
        $this->stockCatalog = $stockCatalog;
    }

    public function getStockCatalog(): ?int
    {
        return $this->stockCatalog;
    }

    public function setStockToShow(int $stockToShow)
    {
        $this->stockToShow = $stockToShow;
    }

    // TODO changed
    public function getStockToShow(): ?int
    {
        //   return $this->stockToShow;
        return $this->stock;
    }

    public function setStockAvailable(int $stockAvailable)
    {
        $this->stockAvailable = $stockAvailable;
    }

    // TODO changed
    public function getStockAvailable(): ?int
    {
        return $this->stockAvailable;
    }

    public function setCategoryName(?string $categoryName)
    {
        $this->categoryName = $categoryName;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setBrandName(?string $brandName)
    {

        $this->brandName = $brandName;
    }

    public function getBrandName(): ?string
    {
        return $this->brandName;
    }

    public function getPartNumber(): ?string
    {
        return $this->partNumber;
    }

    public function setPartNumber(?string $partNumber)
    {
        $this->partNumber = $partNumber;
    }

    public function setCollection(?string $collection)
    {
        $this->collection = $collection;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setPriceCatalog(float $priceCatalog)
    {
        $this->priceCatalog = $priceCatalog;
    }

    public function getPriceCatalog(): ?float
    {
        return $this->priceCatalog;
    }

    public function setPriceWholesale(?float $priceWholesale)
    {
        $this->priceWholesale = $priceWholesale;
    }

    public function getPriceWholesale(): ?float
    {
        return $this->priceWholesale;
    }

    public function setPriceRetail(float $priceRetail)
    {
        $this->priceRetail = $priceRetail;
    }

    // TODO changed
    public function getPriceRetail(): ?float
    {
        return $this->priceRetail;
    }

    public function setPvp(float $pvp)
    {
        $this->pvp = $pvp;
    }

    // TODO changed
    public function getPvp(): ?float
    {
        return $this->pvp;
    }

    public function setDiscount(float $priceRetail)
    {
        $this->priceRetail = $priceRetail;
    }

    public function getDiscount(): ?float
    {
        return $this->priceRetail;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }


    public function setWeight(?float $weight)
    {
        $this->weight = $weight;
    }

    public function setHeight(?float $height)
    {
        $this->height = $height;
    }

    public function setWidth(?float $width)
    {
        $this->width = $width;
    }

    public function setLength(?float $length)
    {
        $this->length = $length;
    }




    public function setWeightPackaging(?float $weightPackaging)
    {
        $this->weightPackaging = $weightPackaging;
    }

    public function setLengthPackaging(?float $lengthPackaging)
    {
        $this->lengthPackaging = $lengthPackaging;
    }

    public function setHeightPackaging(?float $heightPackaging)
    {
        $this->heightPackaging = $heightPackaging;
    }

    public function setWidthPackaging(?float $widthPackaging)
    {
        $this->widthPackaging = $widthPackaging;
    }



    public function getWeightPackaging(): ?float
    {
        return $this->weightPackaging;
    }

    public function getLengthPackaging(): ?float
    {
        return $this->lengthPackaging;
    }

    public function getHeightPackaging(): ?float
    {
        return $this->heightPackaging;
    }

    public function getWidthPackaging(): ?float
    {
        return $this->widthPackaging;
    }

    public function getWeightMaster(): ?float
    {
        return $this->weightMaster;
    }

    public function getLengthMaster(): ?float
    {
        return $this->lengthMaster;
    }

    public function getHeightMaster(): ?float
    {
        return $this->heightMaster;
    }

    public function getWidthMaster(): ?float
    {
        return $this->widthMaster;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setProductLangsSupplier(array $productLangsSupplier)
    {
        $this->productLangsSupplier = $productLangsSupplier;
    }

    // TODO changed
    /** @return ProductLang[] */
    public function getProductLangsSupplier(): ?array
    {
        return $this->productLangsSupplier;
    }

    public function setProductImages(array $productImages)
    {
        $this->productImages = $productImages;
    }

    // TODO changed
    public function getProductImages(): ?array
    {
        return $this->productImages;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setProductAttributes(array $productAttributes)
    {
        $this->productAttributes = $productAttributes;
    }

    public function setProductAttributeValueIfDifferent(string $attributeName, string $attributeValue) : bool
    {
        foreach($this->productAttributes as $attribute){
            if($attribute->getName() == $attributeName){
                if($attribute->getValue() == $attributeValue){
                    return false;
                }else{
                    $attribute->setValue("$attributeValue");
                    return true;
                }
            }
        }
    }

    /**
     * @param mixed $fields
     */
    public function addProductAttribute(ProductAttribute $productAttributes) : void
    {
        $this->productAttributes[] = $productAttributes;
    //    array_push($this->productAttributes, $productAttributes);

    }


    // TODO changed
    /** @return ProductAttribute[] */
    public function getProductAttributes()
    {
        return $this->productAttributes;
    }


    public function getUnitBox(): ?int
    {
        return $this->unitBox;
    }

    public function setUnitBox(int $unitBox)
    {
        $this->unitBox = $unitBox;
    }

    public function getUnitPalet(): ?int
    {
        return $this->unitPalet;
    }

    public function setUnitPalet(int $unitPalet)
    {
        $this->unitPalet = $unitPalet;
    }

    // TODO changed
    public function getAssortment(): ?int
    {
        return $this->assortment;
    }

    public function setAssortment(int $assortment)
    {
        $this->assortment = $assortment;
    }

    // TODO changed
    public function getMinSalesUnit(): ?int
    {
        return $this->minSalesUnit;
    }

    public function setMinSalesUnit(int $minSalesUnit)
    {
        $this->minSalesUnit = $minSalesUnit;
    }
}