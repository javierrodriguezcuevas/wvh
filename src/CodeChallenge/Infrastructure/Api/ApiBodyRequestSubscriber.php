<?php

namespace CodeChallenge\Infrastructure\Api;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ApiBodyRequestSubscriber
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class ApiBodyRequestSubscriber implements EventSubscriberInterface
{
    /** @var array */
    private const DECODE_METHODS = ['PATCH', 'POST', 'PUT', 'DELETE'];

    /**
     * Updates body request to allow json body data.
     *
     * @param GetResponseEvent $event The subscriber event.
     *
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!in_array($request->getMethod(), self::DECODE_METHODS)) {
            return;
        }

        $contentType = $request->headers->get('content-type');

        if ($contentType === 'application/json') {
            $jsonData = json_decode($request->getContent(), true);

            if (!$jsonData) {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Invalid json data.");
            }

            $request->request->replace($jsonData);
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }
}
