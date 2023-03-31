<?php

namespace App\Repository;

use App\Entity\EducationOption;
use App\Entity\Resume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EducationOption>
 *
 * @method EducationOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method EducationOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method EducationOption[]    findAll()
 * @method EducationOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EducationOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EducationOption::class);
    }

    public function add(EducationOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EducationOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $params
     * @param Resume $resume
     * @param EducationOption $educationOption
     * @return EducationOption
     */
    public function createEducationOption(array $params, Resume $resume, EducationOption $educationOption): EducationOption
    {
        $educationOption
            ->setInstitution($params['institution']['value'])
            ->setFaculty($params['faculty']['value'])
            ->setSpecialization($params['specialization']['value'])
            ->setEndYear($params['endYear']['value'])
            ->setResume($resume);
        return $educationOption;
    }
}