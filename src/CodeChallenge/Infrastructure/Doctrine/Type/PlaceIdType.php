<?php

namespace CodeChallenge\Infrastructure\Doctrine\Type;

use CodeChallenge\Domain\Model\Place\Vo\PlaceId;

/**
 * Class PlaceIdType
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class PlaceIdType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'place_id';

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return PlaceId::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeName(): string
    {
        return static::NAME;
    }
}
