<?php

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rapture\Propel\Schema */
    protected $schema;

    public function setUp()
    {
        $this->schema = new \Rapture\Propel\Schema(__DIR__ . '/../schema.xml');
    }

    public function testDatabaseAttribute()
    {
        $this->schema->setDatabaseAttribute('test', 1);

        $this->schema->getSchema()->saveXML('test.xml');

        $this->assertEquals('<?xml version="1.0" encoding="utf-8"?>
<database name="demo" defaultIdMethod="native" defaultPhpNamingMethod="underscore" namespace="Demo\Domain\Model" test="1">
    <table name="user" idMethod="native" phpName="User">
        <column name="id" phpName="Id" type="SMALLINT" size="8" sqlType="mediumint(8) unsigned" primaryKey="true" autoIncrement="true" required="true"/>
        <column name="email" phpName="Email" type="VARCHAR" size="100" required="true"/>
        <column name="password" phpName="Password" type="CHAR" size="60" required="true"/>
        <column name="firstname" phpName="Firstname" type="VARCHAR" size="50" required="true"/>
        <column name="lastname" phpName="Lastname" type="VARCHAR" size="50" required="true"/>
        <column name="middlename" phpName="Middlename" type="VARCHAR" size="50"/>
        <column name="gender" phpName="Gender" type="TINYINT" size="3" sqlType="tinyint(3) unsigned" required="true"/>
        <column name="role" phpName="Role" type="TINYINT" size="3" sqlType="tinyint(3) unsigned" required="true"/>
        <column name="phone" phpName="Phone" type="CHAR" size="9"/>
        <column name="created_at" phpName="CreatedAt" type="TIMESTAMP" required="true" defaultExpr="CURRENT_TIMESTAMP"/>
        <column name="status" phpName="Status" type="TINYINT" size="3" sqlType="tinyint(3) unsigned" required="true" defaultValue="1"/>
        <unique name="email">
            <unique-column name="email"/>
        </unique>
        <unique name="phone">
            <unique-column name="phone"/>
        </unique>
        <vendor type="mysql">
            <parameter name="Engine" value="InnoDB"/>
        </vendor>
    </table>
</database>
', file_get_contents('test.xml'));
    }

    public function tearDown()
    {
        unlink('test.xml');
    }
}
