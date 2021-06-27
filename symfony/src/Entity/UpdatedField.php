<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ProductSystemRepository::class)
 */
class UpdatedField
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="fields")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    private $notification;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $field;


    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $oldValue;


    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $newValue;

    /**
     * UpdatedField constructor.
     * @param Notification $notification
     * @param string $field
     * @param string $oldValue
     * @param string $newValue
     */
    public function __construct(Notification $notification, string $field, string $oldValue, string $newValue)
    {
        $this->notification = $notification;
        $this->id= Uuid::v4();
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }


    /**
     * @return mixed
     */
    public function parentNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     */
    public function setNotification($notification): void
    {
        $this->notification = $notification;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getOldValue(): string
    {
        return $this->oldValue;
    }

    /**
     * @param string $oldValue
     */
    public function setOldValue(string $oldValue): void
    {
        $this->oldValue = $oldValue;
    }

    /**
     * @return string
     */
    public function getNewValue(): string
    {
        return $this->newValue;
    }

    /**
     * @param string $newValue
     */
    public function setNewValue(string $newValue): void
    {
        $this->newValue = $newValue;
    }


}