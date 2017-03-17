<?php

namespace Rapture\Propel\Behavior;

use Rapture\Container\Container;

/**
 * Run method inside preSave() to update createdBy and updatedBy
 */
trait BlameableTrait
{
    /**
     * beforeSaveCreationBehavior
     *
     * Install:
     *
     *  public function preSave(ConnectionInterface $con = null)
     *  {
     *      $this->behaviorBlameable();
     *
     *      return true;
     *  }
     *
     * @return void
     */
    protected function behaviorBlameable()
    {
        $userId = $this->getPropelBehaviorUserId();

        if ($this->isNew()) {
            $this->setCreatedBy($userId);
        }
        else {
            $this->setUpdatedBy($userId);
        }
    }

    /**
     * getPropelBehaviorUserId
     *
     * @return int
     */
    protected function getPropelBehaviorUserId()
    {
        return Container::instance()->get('auth')->user()->getId();
    }

    public function setCreatedBy($id)
    {
        // must be defined
    }

    public function setUpdatedBy($id)
    {
        // must be defined
    }

    public function isNew()
    {
        return true; // must be pre-defined by Propel
    }
}
