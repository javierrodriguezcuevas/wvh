<?php

namespace CodeChallenge\Application\Dto\Post;

use CodeChallenge\Domain\Model\Post\Post;

/**
 * Class PostDto
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class PostDto
{
    /** @var string */
    public $text;

    /**
     * PostDto constructor.
     *
     * @param Post $post A post model.
     */
    public function __construct(Post $post)
    {
        $this->text = $post->text()->value();
    }
}
