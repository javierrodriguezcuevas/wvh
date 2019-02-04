<?php

namespace CodeChallenge\Domain\Model\Post\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PostId
 *
 * @ORM\Embeddable
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class PostId
{
    /**
     * Matches an uuid v1 string.
     *
     * @var string
     */
    const UUID_REGEX = '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[1][0-9A-Fa-f]{3}-[89ABab][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}$/';

    /**
     * @ORM\Id
     * @ORM\Column(type="post_id", name="id", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @var string
     */
    private $value;

    /**
     * PostId constructor.
     *
     * @param string $id The post id.
     *
     * @throws InvalidVoException
     */
    public function __construct(string $id)
    {
        $this->assertInvalidValue($id);

        $this->value = $id;
    }

    /**
     * Gets id string value.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value();
    }

    /**
     * Asserts invalid uuid.
     *
     * @param string $value The value to check.
     *
     * @throws InvalidVoException
     */
    private function assertInvalidValue(string $value)
    {
        if (!preg_match(self::UUID_REGEX, $value)) {
            throw new InvalidVoException("Invalid uuid format.");
        }
    }
}
