<?php

namespace app\models;
namespace app\models\forum;

use Yii;
use yii\base\Model;
use yii\data\Pagination;

class CreateTheme extends Model
{
    const NAME = 'name';
    const CONTENT = 'content';
    const STATUS_OPEN = 0;
    const PAGE_SIZE = 20;

    public $name;
    public $content;
    public $closed;
    public $user_id;
    public $group_id;
    public $theme_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::NAME, self::CONTENT], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            self::NAME => 'Theme Name',
            self::CONTENT => 'Content'
        ];
    }

    /**
     * @param int $g_id
     */
    public function saveNewTheme($g_id = 0)
    {
        $this->group_id = $g_id;
        $this->user_id = (int) Yii::$app->user->identity->getId();

        $newTheme = new Themes();
        $newComment = new Comments();

        $newTheme->name = $this->name;
        $newTheme->closed = self::STATUS_OPEN;
        $newTheme->user_id = $this->user_id;
        $newTheme->group_id = $this->group_id;
        $newTheme->save();

        $newComment->content = $this->content;
        $newComment->user_id = $this->user_id;
        $newComment->theme_id = $newTheme->id;
        $newComment->save();
    }

    /**
     * @param int $g_id
     * @return array
     */
    public function getThemes($g_id = 0)
    {
        $close = false;
        $open = false;
        $query = Themes::find()->where(['group_id' => $g_id]);

        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $query->count(),
        ]);

        $themes = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        if (Yii::$app->user->can('closeThemes')) {
            $close = true;
        }

        if (Yii::$app->user->can('openThemes')) {
            $open = true;
        }

        return ['pagination' => $pagination, 'themes' => $themes, 'close' => $close, 'open' => $open];
    }

    /**
     * @param int $id
     * @param int $status
     * @return int
     */
    public function themeStatus($id = 0, $status = self::STATUS_OPEN)
    {
        $model = Themes::findOne($id);
        $model->closed = $status;
        $model->save();

        return $model->group_id;
    }
}
