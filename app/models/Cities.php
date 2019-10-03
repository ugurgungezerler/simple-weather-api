<?php

class Cities extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $timezone;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("weather-db");
        $this->setSource("Cities");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Cities';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cities[]|Cities|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cities|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function isExists($id)
    {
        $city = Cities::findFirst([
          'conditions' => 'id = ?1',
          'bind' => [
            1 => $id,
          ]
        ]);
        if (!$city) {
            return false;
        }
        return true;
    }

    public static function createCityDummyData()
    {
        $data = [
          ["Bursa", "Europe/Istanbul"],
          ["Istanbul", "Europe/Istanbul"],
          ["New York", "America/New_York"],
          ["Rome", "Europe/Rome"],
          ["Amsterdam", "Europe/Amsterdam"],
          ["Freetown", "Africa/Freetown"],
          ["Sao Paulo", "America/Sao_Paulo"],
          ["Moscow", "Europe/Moscow"],
          ["Mumbai", "Asia/Kolkata"],
          ["Suva", "Pacific/Fiji"],
        ];

        return $data;

    }

    public static function createWeatherInfoDummyData()
    {
        $cities = Cities::find();
        $data = [];
        foreach ($cities as $city) {
            for ($i = 0; $i < 59; $i++) {
                $date = date("Y-m-d", strtotime("+$i day"));
                $data[] = [$city->id, $date, rand(21, 29)];
            }
        }
        return $data;
    }
}
