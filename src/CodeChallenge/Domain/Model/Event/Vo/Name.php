<?php

namespace CodeChallenge\Domain\Model\Event\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Name
 *
 * @ORM\Embeddable
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class Name
{
    /** @var int */
    private const MAX_LENGTH = 250;

    /**
     * @ORM\Column(type="string", name="name", nullable=true)
     *
     * @var string|null
     */
    private $value;

    /**
     * Name constructor.
     *
     * @param string|null $value A name value.
     *
     * @throws InvalidVoException
     */
    public function __construct(?string $value)
    {
        $this->assertInvalidValue($value);

        $this->value = $value;
    }

    /**
     * Gets value.
     *
     * @return string|null
     */
    public function value(): ?string
    {
        return $this->value;
    }

    /**
     * Asserts invalid value.
     *
     * @param string|null $value
     *
     * @throws InvalidVoException
     */
    private function assertInvalidValue(?string $value)
    {
        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidVoException(
                sprintf(
                    "Invalid event name max length %s, current length %s.",
                    self::MAX_LENGTH,
                    strlen($value)
                )
            );
        }
    }
}
