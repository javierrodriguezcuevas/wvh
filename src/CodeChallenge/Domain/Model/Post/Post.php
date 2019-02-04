<?php

namespace CodeChallenge\Domain\Model\Post;

use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Post\Vo\PostId;
use CodeChallenge\Domain\Model\Post\Vo\Text;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Post
 *
 * @ORM\Entity
 * @ORM\Table(name="post")
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class Post
{
    /**
     * @ORM\Embedded(class="CodeChallenge\Domain\Model\Post\Vo\PostId", columnPrefix=false)
     *
     * @var PostId
     */
    private $postId;

    /**
     * @ORM\Embedded(class="CodeChallenge\Domain\Model\Post\Vo\Text", columnPrefix=false)
     *
     * @var Text
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="CodeChallenge\Domain\Model\Event\Event", inversedBy="posts")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *
     * @var Event
     */
    private $event;

    /**
     * Event constructor.
     *
     * @param PostId $postId The post id.
     * @param Event  $event  The event model.
     * @param Text   $text   The post text.
     */
    public function __construct(PostId $postId, Event $event, Text $text)
    {
        $this->postId = $postId;
        $this->event = $event;
        $this->text = $text;
    }

    /**
     * Gets postId.
     *
     * @return PostId
     */
    public function id(): PostId
    {
        return $this->postId;
    }

    /**
     * Gets text.
     *
     * @return Text
     */
    public function text(): Text
    {
        return $this->text;
    }
}
