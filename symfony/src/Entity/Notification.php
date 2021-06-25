<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\UpdatedField;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     */
    private $id;


    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="UpdatedField", mappedBy="notification", cascade={"persist"})
     * @ORM\JoinColumn(name="notification_id", onDelete="CASCADE", nullable=true)
     */
    private $fields;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $isRead;

    /**
     * Notification constructor.
     * @param string $type
     * @param string $description
     */
    public function __construct(string $type, string $description)
    {
        $this->id= Uuid::v4();
        $this->type = $type;
        $this->description = $description;
        $this->createdAt = new \DateTime("now");
        $this->isRead = false;
        $this->fields = array();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }
    /**
     * @param mixed $fields
     */
    public function addFields($fields): void
    {
        array_push($this->fields, $fields);
    }
    /**
     * @param mixed $fields
     */
    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     */
    public function setIsRead(bool $isRead): void
    {
        $this->isRead = $isRead;
    }


}