<?php

namespace CodeChallenge\Infrastructure\Doctrine\Type;

use CodeChallenge\Domain\Model\Post\Vo\PostId;

/**
 * Class PostIdType
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class PostIdType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'post_id';

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return PostId::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeName(): string
    {
        return static::NAME;
    }
}
