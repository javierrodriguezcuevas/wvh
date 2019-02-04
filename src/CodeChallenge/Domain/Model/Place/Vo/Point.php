<?php

namespace CodeChallenge\Domain\Model\Place\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;

/**
 * Description of Point
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class Point
{
    /**
     * Matches a float number between -90|+90 with max 6 decimals using the char '.' as decimal separator.
     *
     * @var string
     */
    const LATITUDE_REGEX = '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/';

    /**
     * Matches a float number between -180|+180 with max 6 decimals using the char '.' as decimal separator.
     *
     * @var string
     */
    const LONGITUDE_REGEX = '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/';

    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    /**
     * Point constructor.
     *
     * @param float $latitude  The point latitude.
     * @param float $longitude The point longitude.
     *
     * @throws InvalidVoException
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->assertInvalidLatitude($latitude);
        $this->assertInvalidLongitude($longitude);

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Gets latitude.
     *
     * @return float
     */
    public function latitude(): float
    {
        return $this->latitude;
    }

    /**
     * Gets longitude.
     *
     * @return float
     */
    public function longitude(): float
    {
        return $this->longitude;
    }

    /**
     * Asserts invalid latitude.
     *
     * @param string $value The value to check.
     *
     * @throws InvalidVoException
     */
    private function assertInvalidLatitude(string $value)
    {
        if (!preg_match(self::LATITUDE_REGEX, $value)) {
            throw new InvalidVoException("Invalid latitude value: '{$value}'.");
        }
    }

    /**
     * Asserts invalid longitude.
     *
     * @param string $value The value to check.
     *
     * @throws InvalidVoException
     */
    private function assertInvalidLongitude(string $value)
    {
        if (!preg_match(self::LONGITUDE_REGEX, $value)) {
            throw new InvalidVoException("Invalid longitude value: '{$value}'.");
        }
    }
}
