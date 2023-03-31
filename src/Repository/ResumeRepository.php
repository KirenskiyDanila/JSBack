<?php

namespace App\Repository;

use App\Entity\Resume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Resume>
 *
 * @method Resume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resume[]    findAll()
 * @method Resume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resume::class);
    }

    public function add(Resume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Resume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $params
     * @return Resume
     */
    public function createResume(array $params, Resume $resume): Resume
    {
        $resume
            ->setProfession($params['profession']['value'])
            ->setCity($params['city']['value'])
            ->setPhoto($params['photo']['value'])
            ->setName($params['name']['value'])
            ->setTelephone($params['telephone']['value'])
            ->setEmail($params['email']['value'])
            ->setDateOfBirth($params['dateOfBirth']['value'])
            ->setEducation($params['education']['value'])
            ->setSalary($params['salary']['value'])
            ->setSkills($params['skills']['value'])
            ->setAbout($params['about']['value'])
            ->setStatus($params['status']['value']);
        return $resume;
    }
}