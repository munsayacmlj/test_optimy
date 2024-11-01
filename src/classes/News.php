<?php

namespace App\Classes;

class News
{
    /**
     * There must not be more than one property declared per statement
     * PSR-12 4.3
     */
    protected $id;
    protected $title;
    protected $body;
    protected $createdAt;

    /**
     * Converted to constructor injection
     * because the properties are required.
     *
     * The setter methods for ID and CreatedAt are removed because
     * those properties are not supposed to be changed.
     */
    public function __construct(int $id, string $title, string $body, string $createdAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->createdAt = $createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter method for the title can stil be used
     * when we want to update the title of a news.
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Setter method for the body can stil be used
     * when we want to update the body of a news.
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
}
