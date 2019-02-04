<?php

namespace CodeChallenge\Infrastructure\Api;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
abstract class ApiController
{
    /** @var SerializerInterface */
    private $serializer;

    /**
     * ApiController constructor.
     *
     * @param SerializerInterface $serializer A class serializer.
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Creates an api response.
     *
     * @param mixed $data       The response data.
     * @param int   $statusCode The response status code
     *
     * @return Response
     */
    protected function createApiResponse($data = [], int $statusCode = Response::HTTP_OK): Response
    {
        $serializedData = $this->serializer->serialize($data, 'json');

        return new JsonResponse($serializedData, $statusCode, [], is_string($serializedData));
    }

    /**
     * Assert required params in an array.
     *
     * @param array $requiredParams The required parameters name.
     * @param array $params         An array of key-value parameters.
     *
     * @return void
     *
     * @throws ApiProblemException
     */
    protected function assertRequiredParameters(array $requiredParams, array $params): void
    {
        if (!empty(array_diff($requiredParams, array_keys($params)))) {
            $this->throwApiProblemException(Response::HTTP_BAD_REQUEST, ApiProblem::TYPE_MISSING_REQUIRE_PARAMETERS);
        }
    }

    /**
     * Throws api problem exception.
     *
     * @param int             $statusCode The status code.
     * @param string          $type       The api problem type.
     * @param \Exception|null $previous   The previous exception.
     *
     * @return void
     *
     * @throws ApiProblemException
     */
    protected function throwApiProblemException(int $statusCode, string $type, \Exception $previous = null): void
    {
        throw new ApiProblemException(new ApiProblem($statusCode, $type), $previous);
    }
}
