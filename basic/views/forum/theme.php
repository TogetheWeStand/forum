<?php

/* @var $this yii\web\View */
/* @var $comments  app\controllers\ForumController */
/* @var $pagination  app\controllers\ForumController */
/* @var $theme  app\controllers\ForumController */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\LinkPager;

$this->title = $theme->closed ? $theme->name . ' (Closed)' : $theme->name;
$this->params['breadcrumbs']['Theme'] = 'Theme';
?>

<div class="forum-header">
    <input type="hidden" name="_frontendCSRF"
           value="<?=Yii::$app->request->getCsrfToken()?>" />
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="forum-body">
    <div class="forum-menu">
        <?php
            if (!Yii::$app->user->isGuest) {
                NavBar::begin();
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-left'],
                        'items' => [
                            ['label' => 'Add Comment', 'url' => ['/forum/add-comment', 'id' => $_GET['id']]]
                        ],
                    ]);
                NavBar::end();
            }
        ?>
        <ul class="comments-list">
            <?php foreach ($comments as $comment): ?>
                <li class="comments-item" id="<?= $comment->id ?>">
                    <div>
                        <div class="user-data">
                            <div class="nick-name">
                                <?= Html::encode("{$comment->user_data->nick_name}") ?>
                            </div>
                            <div class="avatar"><img src=""></div>
                        </div>
                        <div class="content">
                            <?= Html::encode("{$comment->content}") ?>
                        </div>
                    </div>
                    <?php if (!Yii::$app->user->isGuest) { ?>
                        <div class="like">
                            <?php if ($comment->liked) { ?>
                                <img src="/imgs/liked.png">
                            <?php } else { ?>
                                <img src="/imgs/like.png">
                            <?php }  ?>
                        </div>
                        <div class="likes-counter">
                            <?= Html::encode("Likes: " . "{$comment->likes}") ?>
                        </div>
                    <?php } ?>
                    <?php if (!$theme->closed) { ?>
                        <?php if ($comment->editable === true) { ?>
                            <div class="edit-img">
                                <img src="/imgs/edit.png">
                            </div>
                        <?php } ?>
                        <?php if ($comment->deleteable === true) { ?>
                            <div class="delete-img">
                                <img src="/imgs/delete.png">
                            </div>
                        <?php } ?>
                    <?php } ?>
                </li>
            <?php endforeach; ?>
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </ul>
    </div>
</div>