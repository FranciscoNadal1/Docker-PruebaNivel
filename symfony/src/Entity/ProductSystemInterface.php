<?php


namespace App\Entity;

use App\Repository\ProductAttributeRepository;

interface ProductSystemInterface
{
    public function getId(): ?string;

    public function setSku(string $sku);

    public function getSku(): ?string;

    public function setEan13(?string $ean13);

    public function getEan13(): ?string;

    public function setEanVirtual(?string $eanVirtual);

    public function getEanVirtual(): ?string;

    public function setProductEans(array $eans);

    public function getProductEans(): array;

    public function setStock(int $stock);

    public function getStock(): ?int;

    public function setStockCatalog(int $stockCatalog);

    public function getStockCatalog(): ?int;

    public function setStockToShow(int $stockToShow);

    // TODO changed
    public function getStockToShow(): ?int;

    public function setStockAvailable(int $stockAvailable);

    // TODO changed
    public function getStockAvailable(): ?int;

    public function setCategoryName(?string $categoryName);

    public function getCategoryName(): ?string;

    public function setBrandName(?string $brandName);

    public function getBrandName(): ?string;

    public function getPartNumber(): ?string;

    public function setPartNumber(?string $partNumber);

    public function setCollection(?string $collection);

    public function getCollection(): ?string;

    public function setPriceCatalog(float $priceCatalog);

    public function getPriceCatalog(): ?float;

    public function setPriceWholesale(?float $priceWholesale);

    public function getPriceWholesale(): ?float;

    public function setPriceRetail(float $priceRetail);

    // TODO changed
    public function getPriceRetail(): ?float;

    public function setPvp(float $pvp);

    // TODO changed
    public function getPvp(): ?float;

    public function setDiscount(float $priceRetail);

    public function getDiscount(): ?float;

    public function getWeight(): ?float;

    public function getHeight(): ?float;

    public function getWidth(): ?float;

    public function getLength(): ?float;

    public function getWeightPackaging(): ?float;

    public function getLengthPackaging(): ?float;

    public function getHeightPackaging(): ?float;

    public function getWidthPackaging(): ?float;

    public function getWeightMaster(): ?float;

    public function getLengthMaster(): ?float;

    public function getHeightMaster(): ?float;

    public function getWidthMaster(): ?float;

    public function setName(?string $name);

    public function getName(): ?string;

    public function setDescription(?string $description);

    public function getDescription(): ?string;

    public function setProductLangsSupplier(array $productLangsSupplier);


    // TODO changed
    /** @return ProductLang[] */
    public function getProductLangsSupplier(): ?array;

    public function setProductImages(array $productImages);

    // TODO changed
    public function getProductImages(): ?array;

    public function getTax(): ?float;

    public function setProductAttributes(array $productAttributes);

    // TODO changed
    /** @return ProductAttributeRepository[] */
    public function getProductAttributes();

    public function addProductAttribute(ProductAttribute $productAttribute);

    public function getUnitBox(): ?int;

    public function setUnitBox(int $unitBox);

    public function getUnitPalet(): ?int;

    public function setUnitPalet(int $unitPalet);

    // TODO changed
    public function getAssortment(): ?int;

    public function setAssortment(int $assortment);

    // TODO changed
    public function getMinSalesUnit(): ?int;

    public function setMinSalesUnit(int $minSalesUnit);
}