<?php

namespace CodeChallenge\Application\Dto\Event;

use CodeChallenge\Domain\Model\Event\Event;

/**
 * Class EventDto
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class EventDto
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /**
     * EventDto constructor.
     *
     * @param Event $event An event model.
     */
    public function __construct(Event $event)
    {
        $this->id = $event->id()->value();
        $this->name = $event->name()->value();
    }
}
