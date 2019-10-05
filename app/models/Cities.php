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
        $this->setSource("cities");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cities';
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
          ["Bursa", "+03:00"],
          ["Istanbul", "+03:00"],
          ["New York", "-05:00"],
          ["Rome", "+01:00"],
          ["Amsterdam", "+01:00"],
          ["Freetown", "+00:00"],
          ["Sao Paulo", "-03:00"],
          ["Moscow", "+03:00"],
          ["Mumbai", "+05:30"],
          ["Suva", "+12:00"],
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
