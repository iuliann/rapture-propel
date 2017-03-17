<?php

namespace Rapture\Propel;

/**
 * Schema tweaks for PropelORM
 */
class Schema
{
    protected $schema;

    public function __construct($file)
    {
        $this->schema = simplexml_load_file($file);
    }

    /**
     * setDatabaseAttribute
     *
     * Ex: $schema->setDatabaseAttribute('namespace', 'Backend\Domain\Model');
     * Ex: $schema->setDatabaseAttribute('name', 'db_name');
     *
     * @param $attribute
     * @param $value
     *
     * @return $this
     */
    public function setDatabaseAttribute($attribute, $value)
    {
        $this->schema->xpath("/database")[0][$attribute] = $value;

        return $this;
    }

    /**
     * setTableAttribute
     * Ex: Schema::setTableAttribute('user', 'identifierQuoting', true);
     *
     * @param $table
     * @param $attribute
     * @param $value
     *
     * @return $this
     */
    public function setTableAttribute($table, $attribute, $value)
    {
        $this->schema->xpath("/database/table[@name='{$table}']")[0][$attribute] = $value;

        return $this;
    }

    public function addVirtualRelation($table, $column, $refTable, $refColumn, $phpName = null)
    {
        static $index = 0;
        $fkName = "fk{$index}_propel";

        $count = count($this->schema->xpath("/database/table[@name='{$table}']/foreign-key"));

        $this->schema->xpath("/database/table[@name='{$table}']")[0]->addChild('foreign-key');
        $this->schema->xpath("/database/table[@name='{$table}']/foreign-key")[$count]->addAttribute('foreignTable', $refTable);
        $this->schema->xpath("/database/table[@name='{$table}']/foreign-key")[$count]->addAttribute('name', "{$fkName}");
        $this->schema->xpath("/database/table[@name='{$table}']/foreign-key")[$count]->addChild('reference');
        $this->schema->xpath("/database/table[@name='{$table}']/foreign-key[@name='{$fkName}']/reference")[0]->addAttribute('local', $column);
        $this->schema->xpath("/database/table[@name='{$table}']/foreign-key[@name='{$fkName}']/reference")[0]->addAttribute('foreign', $refColumn);

        if ($phpName) {
            $this->schema->xpath("/database/table[@name='{$table}']/foreign-key[@name='{$fkName}']")[0]->addAttribute('phpName', $phpName);
        }

        $index += 2; // don't ask why

        return $this;
    }

    /**
     * setVirtualCombinedPrimaryKey
     *
     * @param $table
     * @param array $columns
     *
     * @return $this
     */
    public function setVirtualCombinedPrimaryKey($table, array $columns)
    {
        foreach ($columns as $column) {
            $this->schema->xpath("/database/table[@name='{$table}']/column[@name='{$column}']")[0]->addAttribute('primaryKey', 'true');
        }

        return $this;
    }

    /**
     * addBehavior
     *
     * @param string $table
     * @param string $behavior
     *
     * @return $this
     */
    public function addBehavior($table, $behavior)
    {
        $count = count($this->schema->xpath("/database/table[@name='{$table}']/behavior"));

        $this->schema->xpath("/database/table[@name='{$table}']")[0]->addChild('behavior');
        $this->schema->xpath("/database/table[@name='{$table}']/behavior")[$count]->addAttribute('name', $behavior);

        return $this;
    }

    public function addBehaviorParameter($table, $behavior, $paramName, $paramValue)
    {
        $count = count($this->schema->xpath("/database/table[@name='{$table}']/behavior[@name='{$behavior}']/parameter"));

        $this->schema->xpath("/database/table[@name='{$table}']/behavior[@name='{$behavior}']")[0]->addChild('parameter');
        $this->schema->xpath("/database/table[@name='{$table}']/behavior[@name='{$behavior}']/parameter")[$count]->addAttribute('name', $paramName);
        $this->schema->xpath("/database/table[@name='{$table}']/behavior[@name='{$behavior}']/parameter")[$count]->addAttribute('value', $paramValue);

        return $this;
    }

    public function setRelationAlias($table, $fkIndex, $phpName)
    {
        $this->schema->xpath("/database/table[@name='{$table}']/foreign-key")[$fkIndex]->addAttribute('phpName', $phpName);

        return $this;
    }

    /**
     * getSchema
     *
     * @return \SimpleXMLElement
     */
    public function getSchema()
    {
        return $this->schema;
    }
}
