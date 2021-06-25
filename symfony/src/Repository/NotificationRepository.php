<?php


namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManager = $this->getEntityManager();
        parent::__construct($registry, Notification::class);
    }

    /** @param Notification */
    public function save($notification) : bool{

        try{
            $this->getEntityManager()->persist($notification);
            $this->getEntityManager()->flush();
        }catch(\Exception $e){
            echo $e;
            return false;
        }
        return true;
    }
}