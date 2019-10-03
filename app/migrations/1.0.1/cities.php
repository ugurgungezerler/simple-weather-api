<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class CitiesMigration_101
 */
class CitiesMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     * @throws \Phalcon\Db\Exception
     */
    public function morph()
    {
        $this->morphTable('cities', [
            'columns' => [
              new Column(
                'id',
                [
                  'type' => Column::TYPE_INTEGER,
                  'unsigned' => true,
                  'notNull' => true,
                  'autoIncrement' => true,
                  'size' => 11,
                  'first' => true
                ]
              ),
              new Column(
                'name',
                [
                  'type' => Column::TYPE_VARCHAR,
                  'notNull' => true,
                  'size' => 255,
                  'after' => 'id'
                ]
              ),
              new Column(
                'timezone',
                [
                  'type' => Column::TYPE_VARCHAR,
                  'notNull' => true,
                  'size' => 255,
                  'after' => 'name'
                ]
              )
            ],
            'indexes' => [
              new Index('PRIMARY', ['id'], 'PRIMARY')
            ],
            'options' => [
              'TABLE_TYPE' => 'BASE TABLE',
              'AUTO_INCREMENT' => '',
              'ENGINE' => 'InnoDB',
              'TABLE_COLLATION' => 'utf8_general_ci'
            ],
          ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
