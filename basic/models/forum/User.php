<?php

namespace app\models;
namespace app\models\forum;

use Yii;
use yii\base\BaseObject;
use yii\web\IdentityInterface;

class User extends BaseObject implements IdentityInterface
{
    public $id;
    public $mail;
    public $pass;
    public $first_name;
    public $last_name;
    public $nick_name;
    public $authKey;
    public $accessToken;
    private static $password;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = Users::find()->where(['id' => $id])->one();

        return isset($user) ? new static($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = Users::find()->where(['token' => $token])->one();

        return isset($user) ? new static($user) : null;
    }

    /**
     * @param string $mail
     * @return static array
     */
    public function findByUserMail($mail = 'example@exemple.com')
    {
        $user = Users::find()->where(['mail' => $mail])->one();

        self::$password = $user->pass;

        return new static($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * @param string $pass
     * @return bool
     */
    public function validatePassword($pass = '')
    {
        return Yii::$app->getSecurity()->validatePassword($pass, self::$password);
    }
}
