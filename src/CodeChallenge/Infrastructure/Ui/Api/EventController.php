<?php

namespace CodeChallenge\Infrastructure\Ui\Api;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Service\Event\CreateEventService;
use CodeChallenge\Application\Service\Event\DetailEventService;
use CodeChallenge\Application\Service\Event\ListEventsService;
use CodeChallenge\Infrastructure\Api\ApiController;
use CodeChallenge\Infrastructure\Api\ApiProblem;
use CodeChallenge\Infrastructure\Api\ApiProblemException;
use Hateoas\Configuration\Relation;
use Hateoas\Representation\CollectionRepresentation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EventController
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class EventController extends ApiController
{
    /**
     * Lists events inside a radius by latitude and longitude.
     *
     * @param Request           $request           The http request.
     * @param ListEventsService $listEventsService The list events service.
     *
     * @return Response
     *
     * @throws ApiProblemException
     */
    public function list(Request $request, ListEventsService $listEventsService): Response
    {
        $this->assertRequiredParameters(['latitude', 'longitude', 'radius'], $request->query->all());

        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');
        $radius = $request->query->getInt('radius');

        try {
            $places = $listEventsService->execute($latitude, $longitude, $radius);
        } catch (ApplicationException $exception) {
            $this->throwApiProblemException(
                Response::HTTP_BAD_REQUEST,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT,
                $exception
            );
        }

        return $this->createApiResponse(new CollectionRepresentation(
            $places->events,
            'events',
            null,
            null,
            null,
            [
                new Relation("self", $request->getRequestUri())
            ]
        ));
    }

    /**
     * Gets an event detail.
     *
     * @param DetailEventService $detailEventService The detail event service.
     * @param string             $eventId            The event id.
     *
     * @return Response
     *
     * @throws ApiProblemException
     */
    public function detail(DetailEventService $detailEventService, string $eventId): Response
    {
        try {
            $eventDto = $detailEventService->execute($eventId);
        } catch (ApplicationException $exception) {
            $this->throwApiProblemException(
                Response::HTTP_BAD_REQUEST,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT,
                $exception
            );
        }

        return $this->createApiResponse($eventDto);
    }

    /**
     * Creates an event model from its own endpoint.
     *
     * @param Request            $request            The http request.
     * @param CreateEventService $createEventService The create event service.
     *
     * @return Response
     *
     * @throws ApiProblemException
     */
    public function create(Request $request, CreateEventService $createEventService): Response
    {
        $this->assertRequiredParameters(['placeId'], $request->request->all());

        $placeId = $request->request->get('placeId');
        $name = $request->request->get('name');

        try {
            $eventDto = $createEventService->execute($placeId, $name);
        } catch (ApplicationException $exception) {
            $this->throwApiProblemException(
                Response::HTTP_BAD_REQUEST,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT,
                $exception
            );
        }

        return $this->createApiResponse($eventDto, Response::HTTP_CREATED);
    }

    /**
     * Creates an event model from nested url definition.
     *
     * @param Request            $request            The http request.
     * @param CreateEventService $createEventService The create event service.
     * @param string             $placeId            The place id.
     *
     * @return Response
     *
     * @throws ApiProblemException
     */
    public function createFromPlaces(
        Request $request,
        CreateEventService $createEventService,
        string $placeId
    ): Response {
        $request->request->set('placeId', $placeId);

        return $this->create($request, $createEventService);
    }
}
