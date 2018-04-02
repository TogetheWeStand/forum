<?php

namespace app\models;
namespace app\models\forum;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Users
 * @package app\models\forum
 * @property int $id
 * @property string $mail
 * @property string $pass
 * @property string $first_name
 * @property string $last_name
 * @property string $nick_name
 */
class Users extends ActiveRecord
{
    /**
     * @return ActiveQuery
     */
    public function getThemes()
    {
        return $this->hasMany(Themes::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCommentsLiked()
    {
        return $this->hasMany(CommentLiked::className(), ['user_id' => 'id']);
    }
}
