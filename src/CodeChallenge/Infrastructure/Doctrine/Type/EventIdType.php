<?php

namespace CodeChallenge\Infrastructure\Doctrine\Type;

use CodeChallenge\Domain\Model\Event\Vo\EventId;

/**
 * Class EventIdType
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class EventIdType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'event_id';

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return EventId::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeName(): string
    {
        return static::NAME;
    }
}
