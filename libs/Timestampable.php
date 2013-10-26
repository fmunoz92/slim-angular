<?php
trait Timestampable
{
    /** @Column(type="datetime") */
    private $created;
    /** @Column(type="datetime") */
    private $updated;

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
        $this->updated = new \DateTime("now");
    }

    /** @PreUpdate */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }
}