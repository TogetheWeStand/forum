<?php

namespace app\models;
namespace app\models\forum;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class CommentLiked
 * @package app\models\forum
 * @property int id
 * @property int user_id
 * @property int comment_id
 * @property int status
 */
class CommentLiked extends ActiveRecord
{
    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comments::className(), ['id' => 'comment_id']);
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{comment_liked}}';
    }
}
