<?php

namespace CodeChallenge\Application\Dto\Event;

use CodeChallenge\Application\Dto\Post\PostDto;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Post\Post;

/**
 * Class EventDetailDto
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class EventDetailDto
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var PostDto[] */
    public $posts;

    /**
     * EventDto constructor.
     *
     * @param Event $event An event model.
     */
    public function __construct(Event $event)
    {
        $this->id = $event->id()->value();
        $this->name = $event->name()->value();
        $this->posts = array_map(function (Post $post) {
            return new PostDto($post);
        }, $event->posts());
    }
}
