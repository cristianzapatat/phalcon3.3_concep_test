<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness;

class User extends Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $passwordl;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field passwordl
     *
     * @param string $passwordl
     * @return $this
     */
    public function setPasswordl($passwordl)
    {
        $this->passwordl = $passwordl;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field passwordl
     *
     * @return string
     */
    public function getPasswordl()
    {
        return $this->passwordl;
    }

    public function beforeSave()
    {   
        
    }

    public function afterSave() {
        
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Por favor ingrese un correo valido'
                ]
            )
        );

        $validator->add(
            'email',
            new Uniqueness(
                [
                    'model' => $this,
                    'message' => 'El correo ingresado ya está en uso'
                ]
            )
        );

        if (strlen($this->getPasswordl()) < 10) {
            $message = new Message(
                "Contraseña demasiado corta",
                "password",
                "length"
            );
            $this->appendMessage($message);
        }

        $statusValidate = $this->validate($validator);
        $countMessages = count(parent::getMessages());
        
        return ($statusValidate && $countMessages === 0);
    }

    public function addMessage($message = '', $field = null, $type = null) {
        if (strlen($message) > 0) {
            $this->appendMessage(new Message(
                    $message,
                    $field,
                    $type
                )
            );
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("testPhalcon");
        $this->setSource("users");
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

    public static function login($email = null, $pass = null) {
        if (!is_null($email) && !is_null($pass)) {

        }
        return array();
    }

}
