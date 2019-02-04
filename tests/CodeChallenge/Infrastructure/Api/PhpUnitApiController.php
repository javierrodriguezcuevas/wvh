<?php

namespace CodeChallenge\Tests\Infrastructure\Api;

use CodeChallenge\Infrastructure\Api\ApiController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PhpUnitApiController
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PhpUnitApiController extends ApiController
{
    /**
     * Public method to test createApiResponse protected method from abstract class.
     *
     * @param array $data
     * @param int   $code
     *
     * @return Response
     */
    public function createApiResponseTest($data = [], int $code = Response::HTTP_OK): Response
    {
        return $this->createApiResponse($data, $code);
    }

    /**
     * Public method to test assertRequiredParameters protected method from abstract class. We return true for testing
     * purpose because protected method returns void.
     *
     * @param array $requiredParams
     * @param array $params
     *
     * @return bool
     */
    public function assertRequiredParametersTest(array $requiredParams, array $params): bool
    {
        $this->assertRequiredParameters($requiredParams, $params);

        return true;
    }
}
