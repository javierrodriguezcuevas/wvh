<?php

namespace CodeChallenge\Infrastructure\Ui\Api;

use CodeChallenge\Infrastructure\Api\ApiController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomepageController
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class HomepageController extends ApiController
{
    /**
     * Shows api homepage.
     *
     * @return Response
     */
    public function index(): Response
    {
        $data = [
            'message' => 'Welcome to the CodeChallenge API.'
        ];

        return $this->createApiResponse($data);
    }
}
