<?php

namespace CodeChallenge\Infrastructure\Persistence\Place;

use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\PlaceRepository;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class DoctrinePlaceRepository
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class DoctrinePlaceRepository extends ServiceEntityRepository implements PlaceRepository
{
    /**
     * DoctrinePlaceRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Place::class);
    }

    /**
     * Gets next model id.
     *
     * @return PlaceId
     *
     * @throws \Exception
     */
    public function nextIdentity(): PlaceId
    {
        return new PlaceId(Uuid::uuid1()->toString());
    }

    /**
     * Gets a place by id.
     *
     * @param PlaceId $id The place id.
     *
     * @return Place
     *
     * @throws ModelNotFoundException
     */
    public function oneById(PlaceId $id): Place
    {
        /** @var Place|null $entity */
        $entity = $this->find(['placeId.value' => $id]);

        if ($entity === null) {
            throw new ModelNotFoundException("Place with id '{$id->value()}' not found.");
        }

        return $entity;
    }

    /**
     * Persists place in repository.
     *
     * @param Place $place The place to persist.
     *
     * @return Place
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Place $place): Place
    {
        $em = $this->getEntityManager();

        $em->persist($place);
        $em->flush($place);

        return $place;
    }
}
