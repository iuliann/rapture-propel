<?php

namespace Rapture\Propel\Behavior;
use Rapture\Helper\Strings;

/**
 * Run method inside preSave() to update slug
 */
trait SluggableTrait
{
    /**
     * Install:
     *
     *  public function preSave(ConnectionInterface $con = null)
     *  {
     *      $this->behaviorSluggable('title');
     *
     *      return true;
     *  }
     *
     * @return void
     */
    protected function behaviorSluggable($columnName = 'title')
    {
        $tableMapClass = self::TABLE_MAP;
        $tableName = $tableMapClass::TABLE_NAME;

        if ($this->isColumnModified($tableName . '.' . $columnName) === true) {
            $slug  = Strings::sluggify(
                $this->getByName($columnName, \Propel\Runtime\Map\TableMap::TYPE_FIELDNAME)
            );
            $query = __CLASS__ . 'Query';

            $suffix = 0;
            $uniqueSlug = $slug;
            while (true) {
                if ($query::create()->filterBy($this->getPropelBehaviourSlugColumn(), $uniqueSlug)->count() == 0) {
                    break;
                }

                $uniqueSlug = $slug . '-' . ++$suffix;
            }

            $this->{"set{$this->getPropelBehaviourSlugColumn()}"}($uniqueSlug);
        }
    }

    protected function getPropelBehaviourSlugColumn()
    {
        return 'Slug';
    }

    public function isColumnModified($name)
    {
        return false; // must be pre-defined by propel
    }

    public function getByName($name, $type)
    {
        return ''; // must be pre-defined by propel
    }
}
