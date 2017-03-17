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

    public function isNew()
    {
        return false; // must be pre-defined by Propel
    }

    public function setCreatedAt($dt)
    {
        // must be defined
    }

    public function setUpdatedAt($dt)
    {
        // must be defined
    }
}
