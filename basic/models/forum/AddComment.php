<?php

namespace app\models;
namespace app\models\forum;

use Yii;
use yii\base\Model;
use yii\data\Pagination;

class AddComment extends Model
{
    const PAGE_SIZE = 20;
    const LIKED_STATUS = 1;
    const EDIT_ACTION = 'edit';
    const DELETE_ACTION = 'delete';
    const ACCESS_SUBJECT = 'Comment';

    public $content;
    public $user_id;
    public $theme_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'content' => 'Comment'
        ];
    }

    /**
     * @param int $theme_id
     */
    public function saveNewComment($theme_id = 0)
    {
        $newComment = new Comments();

        $newComment->content = $this->content;
        $newComment->user_id = Yii::$app->user->identity->getId();
        $newComment->theme_id = (int) $theme_id;
        $newComment->save();
    }

    /**
     * @param int $theme_id
     * @return array
     */
    public function getComments($theme_id = 0)
    {
        $theme = null;
        $current_user = Yii::$app->user->isGuest ? null : Yii::$app->user->identity->getId();

        $query = Comments::find()->where(['theme_id' => $theme_id]);

        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $query->count(),
        ]);

        $comments = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        foreach($comments as $key => $comment) {
            if ($theme === null) {
                $theme = $comment->theme;
            }

            $comments[$key]->user_data = $comment->user;

            foreach($comment->commentsLiked as $liked) {
                if ($liked['user_id'] === (int) $current_user) {
                    $comments[$key]->liked = $liked->status;
                }
            }

            $comments[$key]->editable = ValidatePermission::selfOnlyAccessAction(self::EDIT_ACTION,
                                                                                   self::ACCESS_SUBJECT,
                                                                                        $comment);

            $comments[$key]->deleteable = ValidatePermission::selfOnlyAccessAction(self::DELETE_ACTION,
                                                                                     self::ACCESS_SUBJECT,
                                                                                          $comment);
        }

        return ['pagination' => $pagination, 'comments' => $comments, 'theme' => $theme];
    }

    /**
     * @param int $id
     * @param string $content
     * @return int
     */
    public function updateComment($id = 0, $content = '')
    {
        $model = Comments::findOne($id);
        $model->content = $content;
        $model->save();

        return $model->theme_id;
    }

    /**
     * @param int $id
     * @return int
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteComment($id = 0)
    {
        $model = Comments::findOne($id);
        $theme_id = $model->theme_id;
        $model->delete();

        return $theme_id;
    }

    /**
     * @param int $id
     * @return int
     */
    public function changeLikeStatus($id = 0)
    {
        $user_id = Yii::$app->user->identity->getId();
        $model = CommentLiked::find()->where(['user_id' => $user_id,
                                              'comment_id' => $id])->one();
        if ($model === null) {
            $model = new CommentLiked();
            $model->user_id = (int) $user_id;
            $model->comment_id = $id;
            $model->status = self::LIKED_STATUS;
        } else {
            $model->status = !((int) $model->status);
        }

        $model->save();
        $status = (int) $model->status;

        $model = Comments::findOne($id);

        $model->likes = ($status === self::LIKED_STATUS) ? ++$model->likes : --$model->likes;
        $model->save();

        return $model->theme_id;
    }
}
