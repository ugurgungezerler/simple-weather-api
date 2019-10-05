<?php

class Coupons extends \Phalcon\Mvc\Model
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
    public $code;

    /**
     *
     * @var integer
     */
    public $remain;

    /**
     *
     * @var string
     */
    public $expire_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("weather-db");
        $this->setSource("coupons");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'coupons';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Coupons[]|Coupons|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Coupons|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function createDummyData()
    {
        $data = [];
        for ($i = 0; $i < 99; $i++) {
            $randDay = rand(15, 55);
            $randRemain = rand(2, 45);
            $date = date("Y-m-d", strtotime("+{$randDay} day"));
            $data[] = [self::quickRandom(6), $randRemain, $date];
        }
        return $data;
    }

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public function validOrFail()
    {
        $expires = DateTime::createFromFormat('Y-m-d H:i:s', $this->expire_at);

        if (new DateTime() > $expires) {
            throw new Exception('Coupon is expired');
        }

        if ($this->remain < 1) {
            throw new Exception('Coupon is expired');
        }
    }
}
