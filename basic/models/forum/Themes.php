<?php

namespace app\models;
namespace app\models\forum;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Themes
 * @package app\models\forum
 * @property int id
 * @property string name
 * @property int closed
 * @property int user_id
 * @property int group_id
 */
class Themes extends ActiveRecord
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
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['theme_id' => 'id']);
    }
}
