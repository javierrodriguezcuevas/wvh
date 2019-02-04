<?php

namespace CodeChallenge\Infrastructure\Persistence\Event;

use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\EventRepository;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use CodeChallenge\Domain\Model\Place\Vo\Radius;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class DoctrineEventRepository
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class DoctrineEventRepository extends ServiceEntityRepository implements EventRepository
{
    /**
     * DoctrinePlaceRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Gets next model id.
     *
     * @return EventId
     *
     * @throws \Exception
     */
    public function nextIdentity(): EventId
    {
        return new EventId(Uuid::uuid1()->toString());
    }

    /**
     * Gets a place by id.
     *
     * @param EventId $id The event id.
     *
     * @return Event
     *
     * @throws ModelNotFoundException
     */
    public function oneById(EventId $id): Event
    {
        /** @var Event|null $entity */
        $entity = $this->find(['eventId.value' => $id]);

        if ($entity === null) {
            throw new ModelNotFoundException("Event with id '{$id->value()}' not found.");
        }

        return $entity;
    }

    /**
     * Gets events where place point is inside radius.
     *
     * @param Point  $point  The center point.
     * @param Radius $radius The circle radius.
     *
     * @return Event[]
     */
    public function allByPlacePointInsideRadius(Point $point, Radius $radius): array
    {
        $where = sprintf(
            'ST_INTERSECTS(%3$s, ST_Buffer(ST_GEOMFROMTEXT(\'POINT(%1$s)\'), %2$s))',
            "{$point->latitude()} {$point->longitude()}",
            $radius->value(),
            'p.point'
        );
        $sql = "SELECT e.* FROM place p JOIN event e ON e.place_id = p.id WHERE {$where};";

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Event::class, 'e');

        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getResult();
    }
}
