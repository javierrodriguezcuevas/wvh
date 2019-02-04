<?php

namespace CodeChallenge\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Post\Post;
use CodeChallenge\Domain\Model\Post\Vo\PostId;
use CodeChallenge\Domain\Model\Post\Vo\Text;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of PostFeatureContext
 *
 * @author javierrodriguez
 */
class PostFeatureContext implements Context
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
     * @Given the following posts exists:
     *
     * @param TableNode $table
     *
     * @throws \Exception
     */
    public function theFollowingPostsExists(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $eventId = $row['eventId'];
            $id = (array_key_exists('id', $row)) ? $row['id'] : null;
            $text = (array_key_exists('text', $row)) ? $row['text'] : null;

            unset($row['eventId']);
            unset($row['id']);
            unset($row['text']);

            $this->createPost($eventId, $id, $text);
        }
    }

    /**
     * @param string      $eventId
     * @param string|null $id
     * @param string|null $text
     *
     * @return Post
     *
     * @throws \CodeChallenge\Domain\Exception\InvalidVoException
     * @throws \Exception
     */
    protected function createPost(string $eventId, ?string $id = null, ?string $text = null)
    {
        $event = $this->getEventById($eventId);

        if ($id === null) {
            $id = Uuid::uuid1()->toString();
        }

        $entity = new Post(
            new PostId($id),
            $event,
            new Text($text)
        );

        $this->save($entity);

        return $entity;
    }
    
    /**
     * @param Post $entity
     */
    protected function save(Post $entity)
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
        return $this->doctrine->getRepository(Post::class);
    }
    
    /**
     * @return ObjectManager
     */
    protected function getEntityManager(): ObjectManager
    {
        return $this->doctrine->getManagerForClass(Post::class);
    }

    /**
     * @param string $eventId
     *
     * @return Event
     *
     * @throws \CodeChallenge\Domain\Exception\InvalidVoException
     * @throws \Exception
     */
    private function getEventById(string $eventId)
    {
        $em = $this->doctrine->getManagerForClass(Event::class);

        /** @var Event|null $place */
        $place = $em->find(Event::class, new EventId($eventId));

        if ($place === null) {
            throw new \Exception("Event with id '{$eventId}' not found.");
        }

        return $place;
    }
}
