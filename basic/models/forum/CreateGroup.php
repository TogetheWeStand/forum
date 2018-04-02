<?php

namespace app\models;
namespace app\models\forum;

use Yii;
use yii\base\Model;
use yii\data\Pagination;

class CreateGroup extends Model
{
    const PAGE_SIZE = 20;
    const NAME = 'name';
    public $name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::NAME], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            self::NAME => 'Group Name'
        ];
    }

    public function saveNewGroup()
    {
        $newGroup = new Groups();
        $newGroup->name = $this->name;
        $newGroup->save();
    }

    /**
     * @return array
     */
    public function getAllGroups()
    {
        $add = false;
        $query = Groups::find();

        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $query->count(),
        ]);

        $groups = $query->orderBy(self::NAME)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        if (Yii::$app->user->can('addGroup')) {
            $add = true;
        }

        return ['pagination' => $pagination, 'groups' => $groups, 'add' => $add];
    }
}
