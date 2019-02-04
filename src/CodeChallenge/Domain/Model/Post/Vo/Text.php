<?php

namespace CodeChallenge\Domain\Model\Post\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Text
 *
 * @ORM\Embeddable
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class Text
{
    /** @var int */
    private const MAX_LENGTH = 500;

    /**
     * @ORM\Column(type="text", name="text", length=500, nullable=false)
     *
     * @var string
     */
    private $value;

    /**
     * Text constructor.
     *
     * @param string $value A text value.
     *
     * @throws InvalidVoException
     */
    public function __construct(string $value)
    {
        $this->assertInvalidValue($value);

        $this->value = $value;
    }
    
    /**
     * Gets value.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Asserts invalid value.
     *
     * @param string $value The value to check.
     *
     * @throws InvalidVoException
     */
    private function assertInvalidValue(string $value)
    {
        if (empty(trim($value))) {
            throw new InvalidVoException("Invalid text, cannot be empty.");
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidVoException(
                sprintf(
                    "Invalid text max length %s, current length %s.",
                    self::MAX_LENGTH,
                    strlen($value)
                )
            );
        }
    }
}
