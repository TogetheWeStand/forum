<?php

namespace app\models;
namespace app\models\forum;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Groups
 * @package app\models\forum
 * @property int id
 * @property string name
 */
class Groups extends ActiveRecord
{
    /**
     * @return ActiveQuery
     */
    public function getThemes()
    {
        return $this->hasMany(Themes::className(), ['group_id' => 'id']);
    }
}
