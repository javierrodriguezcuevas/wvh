<?php

namespace CodeChallenge\Infrastructure\Api;

use CodeChallenge\Infrastructure\InfrastructureException;

/**
 * Class ApiProblemException
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class ApiProblemException extends InfrastructureException
{
    /** @var ApiProblem */
    private $apiProblem;

    /**
     * ApiProblemException constructor.
     *
     * @param ApiProblem      $apiProblem The api problem.
     * @param \Exception|null $previous   The previous exception.
     */
    public function __construct(ApiProblem $apiProblem, \Exception $previous = null)
    {
        $this->apiProblem = $apiProblem;

        parent::__construct($apiProblem->getTitle(), $apiProblem->getStatusCode(), $previous);
    }

    /**
     * Gets api problem.
     *
     * @return ApiProblem
     */
    public function getApiProblem(): ApiProblem
    {
        return $this->apiProblem;
    }
}
