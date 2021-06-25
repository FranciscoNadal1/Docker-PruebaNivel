<?php


namespace App\Repository;

use App\Entity\ProductSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSystem[]    findAll()
 * @method ProductSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSystemRepository extends ServiceEntityRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManager = $this->getEntityManager();
        parent::__construct($registry, ProductSystem::class);
    }

    /** @param ProductSystem */
    public function save($productSystem) : bool{

        try{
            $this->getEntityManager()->persist($productSystem);
            $this->getEntityManager()->flush();
        }catch(\Exception $e){
            echo $e;
            return false;
        }
        return true;
    }
}