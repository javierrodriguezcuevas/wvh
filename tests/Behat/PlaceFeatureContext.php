<?php

namespace CodeChallenge\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\Vo\Name;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of PlaceFeatureContext
 *
 * @author javierrodriguez
 */
class PlaceFeatureContext implements Context
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
     * @Given the following places exists:
     *
     * @param TableNode $table
     *
     * @throws \Exception
     */
    public function theFollowingPlacesExists(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $latitude = $row['latitude'];
            $longitude = $row['longitude'];
            $id = (array_key_exists('id', $row)) ? $row['id'] : null;
            $name = (array_key_exists('name', $row)) ? $row['name'] : null;

            unset($row['latitude']);
            unset($row['longitude']);
            unset($row['id']);
            unset($row['name']);

            $this->createPlace($latitude, $longitude, $id, $name);
        }
    }

    /**
     * @param float       $latitude
     * @param float       $longitude
     * @param string|null $id
     * @param string|null $name
     *
     * @return Place
     *
     * @throws \CodeChallenge\Domain\Exception\InvalidVoException
     * @throws \Exception
     */
    protected function createPlace(float $latitude, float $longitude, ?string $id = null, ?string $name = null)
    {
        if ($id === null) {
            $id = Uuid::uuid1()->toString();
        }

        $entity = new Place(
            new PlaceId($id),
            new Point($latitude, $longitude),
            new Name($name)
        );
        
        $this->save($entity);

        return $entity;
    }
    
    /**
     * @param Place $entity
     */
    protected function save(Place $entity)
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
        return $this->doctrine->getRepository(Place::class);
    }
    
    /**
     * @return ObjectManager
     */
    protected function getEntityManager(): ObjectManager
    {
        return $this->doctrine->getManagerForClass(Place::class);
    }
}
