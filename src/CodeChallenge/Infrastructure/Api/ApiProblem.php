<?php

namespace CodeChallenge\Infrastructure\Api;

/**
 * Class ApiProblem
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class ApiProblem
{
    /** @var string */
    const TYPE_INVALID_REQUEST_BODY_FORMAT  = 'invalid_body_format';

    /** @var string */
    const TYPE_MISSING_REQUIRE_PARAMETERS  = 'missing_required_parameters';

    /** @var array */
    private static $titles = array(
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid data sent',
        self::TYPE_MISSING_REQUIRE_PARAMETERS => 'Missing required parameters',
    );

    /** @var int */
    private $statusCode;

    /** @var string */
    private $type;

    /** @var string */
    private $title;

    /**
     * ApiProblem constructor.
     *
     * @param int    $statusCode The status code.
     * @param string $type       The api problem type.
     */
    public function __construct(int $statusCode, string $type)
    {
        if (!isset(self::$titles[$type])) {
            throw new \InvalidArgumentException('No title for type '.$type);
        }

        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->title = self::$titles[$type];
    }

    /**
     * Gets api problem as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->statusCode,
            'type' => $this->type,
            'title' => $this->title,
        ];
    }

    /**
     * Gets api problem status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Gets api problem title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
