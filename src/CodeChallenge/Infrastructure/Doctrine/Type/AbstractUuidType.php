<?php

namespace CodeChallenge\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;

/**
 * Class AbstractUuidType
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
abstract class AbstractUuidType extends UuidBinaryOrderedTimeType
{
    /**
     * Gets class full name.
     *
     * @return string
     */
    abstract public function getClassName(): string;

    /**
     * Gets field type name.
     *
     * @return string
     */
    abstract public function getTypeName(): string;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getTypeName();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        /** @var UuidInterface|null $value */
        $value = parent::convertToPHPValue($value, $platform);

        if (empty($value)) {
            return null;
        }

        $class = $this->getClassName();

        if (!class_exists($class)) {
            throw new ConversionException($value, $this->getName());
        }

        try {
            $value = new $class($value->toString());
        } catch (\Exception $exception) {
            throw new ConversionException($value, $this->getName());
        }

        return $value;
    }
}
