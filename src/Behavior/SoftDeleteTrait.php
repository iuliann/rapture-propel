<?php

namespace Rapture\Propel\Behavior;

use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;

/**
 * SoftDelete will update `status` column to 0
 */
trait SoftDeleteTrait
{
    /**
     * beforeSaveCreationBehavior
     *
     * Install:
     *
     *  public function delete(ConnectionInterface $con = null)
     *  {
     *      $this->behaviorSoftDelete($con);
     *  }
     *
     * @param ConnectionInterface $con
     *
     * @throws PropelException
     * @return void
     */
    protected function behaviorSoftDelete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException('This object has already been deleted.');
        }

        if ($con === null) {
            $tableMap = self::TABLE_MAP;
            $con = Propel::getServiceContainer()->getWriteConnection($tableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con){
            $ret = $this->preDelete($con);
            if ($ret) {
                $this->{"set{$this->getPropelBehaviourStatusColumn()}"}($this->getPropelBehaviourStatusDelete());
                $isDeleted = $this->save();
                $this->postDelete($con);
                $this->setDeleted($isDeleted);
            }
        });
    }

    protected function getPropelBehaviourStatusColumn()
    {
        return 'Status';
    }

    protected function getPropelBehaviourStatusDelete()
    {
        return 0;
    }
}
