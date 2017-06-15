<?php

namespace Rapture\Propel\Behavior;

/**
 * Run method inside preSave() to update created_at and updated_at
 */
trait TimestampTrait
{
    /**
     * beforeSaveCreationBehavior
     *
     * Install:
     *
     *  public function preSave(ConnectionInterface $con = null)
     *  {
     *      $this->behaviorTimestamp();
     *
     *      return true;
     *  }
     *
     * @return void
     */
    protected function behaviorTimestamp()
    {
        if ($this->isNew()) {
            $this->setCreatedAt(new \DateTime());
        }
        else {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return false; // must be pre-defined by Propel
    }

    /**
     * @param \DateTime $dt Date and time
     *
     * @return void
     */
    public function setCreatedAt($dt)
    {
        // must be defined
    }

    /**
     * @param \DateTime $dt Date and time
     *
     * @return void
     */
    public function setUpdatedAt($dt)
    {
        // must be defined
    }
}
