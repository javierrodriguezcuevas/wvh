<?php

namespace CodeChallenge\Domain\Model\Place\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;

/**
 * Description of Radius
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class Radius
{
    /** @var string */
    private $value;

    /**
     * Radius constructor.
     *
     * @param float $radius A radius value.
     *
     * @throws InvalidVoException
     */
    public function __construct(float $radius)
    {
        $this->assertInvalidValue($radius);

        $this->value = $radius / 100;
    }

    /**
     * Gets value.
     *
     * @return float
     */
    public function value(): float
    {
        return $this->value;
    }

    /**
     * Asserts invalid radius.
     *
     * @param int $value The value to check.
     *
     * @throws InvalidVoException
     */
    private function assertInvalidValue(int $value)
    {
        if ($value <= 0) {
            throw new InvalidVoException("Radius cannot be less or equals to zero.");
        }
    }
}
