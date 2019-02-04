<?php

namespace CodeChallenge\Domain\Model\Place;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name as EventName;
use CodeChallenge\Domain\Model\Place\Vo\Name;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Place
 *
 * @ORM\Entity(repositoryClass="CodeChallenge\Infrastructure\Persistence\Doctrine\Place\PlaceRepository")
 * @ORM\Table(name="place", indexes={@ORM\Index(name="point_idx", columns={"point"}, flags={"spatial"})})
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class Place
{
    /**
     * @ORM\Embedded(class="CodeChallenge\Domain\Model\Place\Vo\PlaceId", columnPrefix=false)
     *
     * @var PlaceId
     */
    private $placeId;

    /**
     * @ORM\Column(type="point", name="point")
     *
     * @var Point
     */
    private $point;

    /**
     * @ORM\Embedded(class="CodeChallenge\Domain\Model\Place\Vo\Name", columnPrefix=false)
     *
     * @var Name
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="CodeChallenge\Domain\Model\Event\Event", mappedBy="place", cascade={"persist"})
     *
     * @var Event[]
     */
    private $events;

    /**
     * Place constructor.
     *
     * @param PlaceId   $placeId The point id.
     * @param Point     $point   The place point.
     * @param Name|null $name    An optional place name.
     *
     * @throws InvalidVoException
     */
    public function __construct(PlaceId $placeId, Point $point, ?Name $name = null)
    {
        $this->placeId = $placeId;
        $this->point = $point;
        $this->name = ($name === null) ? new Name('') : $name;
        $this->events = new ArrayCollection();
    }

    /**
     * Gets placeId.
     *
     * @return PlaceId
     */
    public function id(): PlaceId
    {
        return $this->placeId;
    }

    /**
     * Gets geo point.
     *
     * @return Point
     */
    public function point(): Point
    {
        return $this->point;
    }

    /**
     * Gets name.
     *
     * @return Name|null
     */
    public function name(): ?Name
    {
        return $this->name;
    }

    /**
     * Gets events.
     *
     * @return Event[]
     */
    public function events(): array
    {
        return $this->events->toArray();
    }

    /**
     * Adds an event to the place.
     *
     * @param EventId        $eventId An event id.
     * @param EventName|null $name    An event name.
     *
     * @return Event
     *
     * @throws InvalidVoException
     */
    public function addEvent(EventId $eventId, ?EventName $name = null): Event
    {
        $events = $this->events->filter(function (Event $event) use ($eventId) {
            return $event->id() === $eventId;
        });

        $event = $events->first();

        if (empty($event)) {
            $event = new Event($eventId, $this, $name);

            $this->events[] = $event;
        }

        return $event;
    }
}
