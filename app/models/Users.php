<?php

use Phalcon\Db\Column;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Users extends \Phalcon\Mvc\Model
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
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $lang;

    /**
     *
     * @var string
     */
    public $os;

    /**
     *
     * @var string
     */
    public $device_token;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $city_id;

//    public function metaData()
//    {
//        return [
//          MetaData::MODELS_DATA_TYPES => [
//            'id' => Column::TYPE_INTEGER,
//            'email' => Column::TYPE_VARCHAR,
//            'city_id' => Column::TYPE_INTEGER,
//            'is_premium' => Column::TYPE_BOOLEAN,
//          ],
//        ];
//    }

    public function beforeValidationOnCreate()
    {
        $this->_isCreate = true;
    }

    public function beforeValidationOnUpdate()
    {
        $this->_isCreate = false;
    }

    public function validation()
    {
        $validator = new Validation();
        if ($this->_isCreate === true) {
            $validator->add(
              'email',
              new EmailValidator(
                [
                  'model' => $this,
                  'message' => 'Please enter a correct email address',
                ]
              )
            );

            $validator->add(
              'email',
              new UniquenessValidator(
                [
                  "model" => new Users(),
                  "message" => "Email must be unique",
                ]
              )
            );

            $validator->add(
              "password",
              new StringLength(
                [
                  "max" => 100,
                  "min" => 8,
                  "messageMaximum" => "Maximum password length must be 100",
                  "messageMinimum" => "Minimum password length must be 8",
                ]
              )
            );
        }

        $validator->add(
          'os',
          new InclusionIn(
            [
              "message" => "Os must be ios or android",
              "domain" => ["ios", "android"],
            ]
          )
        );

        $validator->add(
          'lang',
          new InclusionIn(
            [
              "message" => "Language must be tr or en",
              "domain" => ["tr", "en"],
            ]
          )
        );


        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->useDynamicUpdate(true);

        $this->setSchema("weather-db");
        $this->setSource("users");

        $this->addBehavior(
          new Timestampable(
            [
              'beforeCreate' => [
                'field' => 'created_at',
                'format' => 'Y-m-d H:i',
              ]
            ]
          )
        );
        $this->skipAttributesOnUpdate(array('email', 'password'));

    }

    public function validationHasFailed()
    {
        return false;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function createDummyData()
    {
        $di = \Phalcon\DI::getDefault();

        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = ["john{$i}@gmail.com", $di->get('security')->hash(12345), 'tr', 'ios', rand(1, 10)];
        }
        return $data;
    }


}
