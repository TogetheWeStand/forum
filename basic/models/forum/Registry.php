<?php

namespace app\models;
namespace app\models\forum;

use Yii;
use yii\base\Model;

class Registry extends Model
{
    public $nick_name;
    public $first_name;
    public $last_name;
    public $mail;
    public $pass;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['nick_name', 'first_name', 'last_name', 'mail', 'pass'], 'required'],
            ['mail', 'email'],
            ['mail', 'validateAttribute'],
            ['nick_name', 'validateAttribute'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'nick_name' => 'Your Nick',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'mail' => 'Email',
            'pass' => 'Password',
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateAttribute($attribute = '')
    {
        if (!$this->hasErrors()) {
            $alreadyExists = Users::find()->where([$attribute => $this->$attribute])->one();

            if (gettype($alreadyExists->$attribute)  !== 'NULL')
                $this->addError($attribute, 'Already exists.');
        }
    }

    /**
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function saveNewUser()
    {
        $newUser = new Users();
        $newUser->mail = $this->mail;
        $newUser->pass = Yii::$app->security->generatePasswordHash($this->pass);
        $newUser->nick_name = $this->nick_name;
        $newUser->first_name = $this->first_name;
        $newUser->last_name = $this->last_name;
        $newUser->save();

        //Add role for new user
        $auth = Yii::$app->authManager;
        $user = $auth->getRole('user');
        $auth->assign($user, $newUser->id);
    }
}
