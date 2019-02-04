<?php

namespace CodeChallenge\Domain\Model\Event;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Post\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event
 *
 * @ORM\Entity
 * @ORM\Table(name="event")
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class Event
{
    /**
     * @ORM\Embedded(class="CodeChallenge\Domain\Model\Event\Vo\EventId", columnPrefix=false)
     *
     * @var EventId
     */
    private $eventId;

    /**
     * @ORM\ManyToOne(targetEntity="CodeChallenge\Domain\Model\Place\Place", inversedBy="events")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     *
     * @var Place
     */
    private $place;

    /**
     * @ORM\Embedded(class="CodeChallenge\Domain\Model\Event\Vo\Name", columnPrefix=false)
     *
     * @var Name
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="CodeChallenge\Domain\Model\Post\Post", mappedBy="event", cascade={"persist"})
     *
     * @var Post[]
     */
    private $posts;

    /**
     * Event constructor.
     *
     * @param EventId $eventId The event id.
     * @param Place   $place   The place the event belongs.
     * @param Name|null $name  An optional place name.
     *
     * @throws InvalidVoException
     */
    public function __construct(EventId $eventId, Place $place, ?Name $name = null)
    {
        $this->eventId = $eventId;
        $this->place = $place;
        $this->name = ($name === null) ? new Name('') : $name;
        $this->posts = new ArrayCollection();
    }

    /**
     * Gets eventId.
     *
     * @return EventId
     */
    public function id(): EventId
    {
        return $this->eventId;
    }

    /**
     * Gets place.
     *
     * @return Place
     */
    public function place(): Place
    {
        return $this->place;
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
     * Gets posts.
     *
     * @return Post[]
     */
    public function posts(): array
    {
        return $this->posts->toArray();
    }
}
