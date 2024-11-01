<?php

namespace App\Classes;

class Comment
{
    /**
     * There must not be more than one property declared per statement
     * PSR-12 4.3
     */
    protected $id;
    protected $body;
    protected $createdAt;
    protected $newsId;

    /**
     * Converted to constructor injection
     * because the properties are required.
     *
     * The setter methods for $id and $createdAt, and $newsId are removed because
     * those properties are not supposed to be changed.
     */
    public function __construct(int $id, string $body, string $createdAt, int $newsId)
    {
        $this->id = $id;
        $this->body = $body;
        $this->createdAt = $createdAt;
        $this->newsId = $newsId;
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter method for the body can stil be used
     * when we want to update the body of a comment.
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getNewsId()
    {
        return $this->newsId;
    }
}
