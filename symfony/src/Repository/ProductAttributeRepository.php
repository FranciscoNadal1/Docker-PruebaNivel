<?php


namespace App\Repository;


use App\Entity\ProductAttribute;
use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ProductAttributeRepository extends ServiceEntityRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManager = $this->getEntityManager();
        parent::__construct($registry, Notification::class);
    }

    /** @param ProductAttributeRepository */
    public function save($attributes) : bool{

        try{
            $this->getEntityManager()->persist($attributes);
            $this->getEntityManager()->flush();
        }catch(\Exception $e){
            echo $e;
            return false;
        }
        return true;
    }
}