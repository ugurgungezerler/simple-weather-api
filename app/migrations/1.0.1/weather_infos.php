<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;
use Phalcon\Application;
/**
 * Class WeatherInfosMigration_101
 */
class WeatherInfosMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     * @throws \Phalcon\Db\Exception
     */
    public function morph()
    {
        $this->morphTable('weather_infos', [
            'columns' => [
              new Column(
                'id',
                [
                  'type' => Column::TYPE_INTEGER,
                  'unsigned' => true,
                  'notNull' => true,
                  'autoIncrement' => true,
                  'size' => 10,
                  'first' => true
                ]
              ),
              new Column(
                'city_id',
                [
                  'type' => Column::TYPE_INTEGER,
                  'unsigned' => true,
                  'notNull' => true,
                  'size' => 10,
                  'after' => 'id'
                ]
              ),
              new Column(
                'date',
                [
                  'type' => Column::TYPE_DATE,
                  'notNull' => true,
                  'size' => 1,
                  'after' => 'city_id'
                ]
              ),
              new Column(
                'celsius',
                [
                  'type' => Column::TYPE_INTEGER,
                  'notNull' => true,
                  'size' => 3,
                  'after' => 'date'
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
