<?php

namespace app\models;
namespace app\models\forum;

use Yii;
use yii\base\Model;
use app\models\forum\User;

/**
 * @property User|null $user This property is read-only.
 */
class Login extends Model
{
    public $mail;
    public $pass;
    public $rememberMe = true;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['mail', 'pass'], 'required'],
            ['rememberMe', 'boolean'],
            ['pass', 'validatePassword'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'mail' => 'Email',
            'pass' => 'Password',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $currentUser = $this->getUser();
            $user = new User();

            if (!$currentUser || !$user->validatePassword($this->pass)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        $user = new User();

        if ($this->_user === false) {
            $this->_user = $user->findByUserMail($this->mail);
        }

        return $this->_user;
    }
}
