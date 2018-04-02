<?php

/* @var $this yii\web\View */
/* @var $groups app\models\forum\CreateGroup */
/* @var $add  app\controllers\ForumController */
/* @var $pagination  app\controllers\ForumController */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\LinkPager;

$this->title = 'Home';
$this->params['breadcrumbs']['Home'] = '';
?>
<div class="forum-header">
    <input type="hidden" name="_frontendCSRF"
           value="<?=Yii::$app->request->getCsrfToken()?>" />
    <h1><?= Html::encode($this->title) ?></h1>
    <p><strong>Добро пожаловат на тестовый форум!</strong></p>
</div>
<div class="forum-body">
    <div class="forum-menu">
        <?php
            if (!Yii::$app->user->isGuest && $add) {
                NavBar::begin();
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-left'],
                        'items' => [
                            ['label' => 'Create Group', 'url' => ['/forum/create-group']]
                        ],
                    ]);
                NavBar::end();
            }
        ?>

        <label><strong>Groups</strong></label>
        <ul class="group-list">
            <?php foreach ($groups as $group): ?>
                <li class="group-item" id="<?=$group->id?>">
                    <?= Html::encode("{$group->name}") ?>
                </li>
            <?php endforeach; ?>
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </ul>
    </div>
</div>
