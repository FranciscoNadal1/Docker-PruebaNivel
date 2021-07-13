<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ProductAttribute::class)
 */
class ProductAttribute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductSystem", inversedBy="productAttributes")
     * @ORM\JoinColumn(name="relatedProduct_id", referencedColumnName="id")
     */
    private $relatedProductSystem;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $name;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $value;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $attributeID;

    /**
     * ProductAttribute constructor.
     * @param ProductSystem $relatedProductSystem
     * @param string $name
     * @param string $value
     * @param string|null $attributeID
     */
    public function __construct(ProductSystem $relatedProductSystem, string $name, ?string $value,  ?string $attributeID)
    {
        $this->relatedProductSystem = $relatedProductSystem;
        $this->name = $name;
        if($value!=null)
            $this->value = $value;
        if($attributeID!=null)
            $this->attributeID = $attributeID;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function getAttributeID(): ?string
    {
        return $this->attributeID;
    }

    /**
     * @param string $attributeID
     */
    public function setAttributeID(string $attributeID): void
    {
        $this->attributeID = $attributeID;
    }


}