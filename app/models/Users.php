<?php

use Phalcon\Mvc\Model\Behavior\Timestampable;
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
     * Validations and business logic
     *
     * @return boolean
     */

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
        $this->setSource("Users");

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
        return 'Users';
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

}
