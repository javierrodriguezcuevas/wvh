<?php

namespace CodeChallenge\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of EventFeatureContext
 *
 * @author javierrodriguez
 */
class EventFeatureContext implements Context
{   
    /**
     * @var RegistryInterface 
     */
    private $doctrine;
    
    /**
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Given the following events exists:
     *
     * @param TableNode $table
     *
     * @throws \Exception
     */
    public function theFollowingEventsExists(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $placeId = $row['placeId'];
            $id = (array_key_exists('id', $row)) ? $row['id'] : null;
            $name = (array_key_exists('name', $row)) ? $row['name'] : null;

            unset($row['placeId']);
            unset($row['id']);
            unset($row['name']);

            $this->createEvent($placeId, $id, $name);
        }
    }

    /**
     * @param string      $placeId
     * @param string|null $id
     * @param string|null $name
     *
     * @return Event
     *
     * @throws \CodeChallenge\Domain\Exception\InvalidVoException
     * @throws \Exception
     */
    protected function createEvent(string $placeId, ?string $id = null, ?string $name = null)
    {
        $place = $this->getPlaceById($placeId);

        if ($id === null) {
            $id = Uuid::uuid1()->toString();
        }

        $entity = new Event(
            new EventId($id),
            $place,
            new Name($name)
        );
        
        $this->save($entity);

        return $entity;
    }
    
    /**
     * @param Event $entity
     */
    protected function save(Event $entity)
    {
        $em = $this->getEntityManager();
        
        $em->persist($entity);
        $em->flush();
    }
    
    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->doctrine->getRepository(Event::class);
    }
    
    /**
     * @return ObjectManager
     */
    protected function getEntityManager(): ObjectManager
    {
        return $this->doctrine->getManagerForClass(Event::class);
    }

    /**
     * @param string $placeId
     *
     * @return Place
     *
     * @throws \CodeChallenge\Domain\Exception\InvalidVoException
     * @throws \Exception
     */
    private function getPlaceById(string $placeId)
    {
        $em = $this->doctrine->getManagerForClass(Place::class);

        /** @var Place|null $place */
        $place = $em->find(Place::class, new PlaceId($placeId));

        if ($place === null) {
            throw new \Exception("Place with id '{$placeId}' not found.");
        }

        return $place;
    }
}
