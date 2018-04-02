<?php

namespace app\models;
namespace app\models\forum;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Comments
 * @package app\models\forum
 * @property int id
 * @property string content
 * @property int user_id
 * @property int theme_id
 * @property int likes
 */
class Comments extends ActiveRecord
{
    public $user_data;
    public $theme_data;
    public $editable;
    public $deleteable;
    public $liked;

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
    public function getTheme()
    {
        return $this->hasOne(Themes::className(), ['id' => 'theme_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCommentsLiked()
    {
        return $this->hasMany(CommentLiked::className(), ['comment_id' => 'id']);
    }
}
