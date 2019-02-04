<?php

namespace CodeChallenge\Infrastructure\Ui\Api;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Service\Place\CreatePlaceService;
use CodeChallenge\Infrastructure\Api\ApiController;
use CodeChallenge\Infrastructure\Api\ApiProblem;
use CodeChallenge\Infrastructure\Api\ApiProblemException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlaceController
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class PlaceController extends ApiController
{
    /**
     * Creates a place model.
     *
     * @param Request            $request
     * @param CreatePlaceService $createPlaceService
     *
     * @return Response
     *
     * @throws ApiProblemException
     */
    public function create(Request $request, CreatePlaceService $createPlaceService): Response
    {
        $this->assertRequiredParameters(['latitude', 'longitude'], $request->request->all());

        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        $name = $request->request->get('name');

        try {
            $placeDto = $createPlaceService->execute($latitude, $longitude, $name);
        } catch (ApplicationException $exception) {
            $this->throwApiProblemException(
                Response::HTTP_BAD_REQUEST,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT,
                $exception
            );
        }

        return $this->createApiResponse($placeDto, Response::HTTP_CREATED);
    }
}
