<?php

namespace App\Models;

use App\Core\Model;

class BilletsModel extends Model
{
    protected $id;
    protected $title;
    protected $content;
    protected $publish_at;
    protected $published;
    protected $users_id;
    protected $chapter_image;

    public function __construct()
    {
        $this->table = 'billets';
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param [type] $id
     * @return self
     */
    public function setId($id):self
    {
        $this->id = $id;
        return $this;
    }
    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param [type] $title
     * @return self
     */
    public function setTitle($title):self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param [type] $content
     * @return self
     */
    public function setContent($content):self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the value of publish_at
     */
    public function getPublish_at()
    {
        return $this->publish_at;
    }

    /**
     * Set the value of publish_at
     *
     * @param [type] $publish_at
     * @return self
     */
    public function setPublish_at($publish_at):self
    {
        $this->publish_at = $publish_at;
        return $this;
    }

    /**
     * Get the value of published
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set the value of published
     *
     * @param [type] $published
     * @return self
     */
    public function setPublished($published):self
    {
        $this->published = $published;
        return $this;
    }

    /**
     * Get the value of users_id
     */
    public function getUsers_id()
    {
        return $this->users_id;
    }

    /**
     * Set the value of users_id
     *
     * @param [type] $users_id
     * @return self
     */
    public function setUsers_id($users_id):self
    {
        $this->users_id = $users_id;
        return $this;
    }

    public function getChapterImage()
    {
        return $this->chapter_image;
    }

    public function setChapterImage($chapter_image)
    {
        $this->chapter_image = $chapter_image;
        return $this;
    }
}

?>