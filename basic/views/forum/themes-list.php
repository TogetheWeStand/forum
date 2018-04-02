<?php

/* @var $this yii\web\View */
/* @var $themes  app\controllers\ForumController */
/* @var $close  app\controllers\ForumController */
/* @var $open  app\controllers\ForumController */
/* @var $pagination  app\controllers\ForumController */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\LinkPager;

$this->title = 'Themes';
$this->params['breadcrumbs']['Themes'] = $this->title;
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
                            ['label' => 'Create Theme', 'url' => ['/forum/create-theme', 'g_id' => $_GET['g_id']]]
                        ],
                    ]);
                NavBar::end();
            }
        ?>
        <ul class="themes-list">
            <?php foreach ($themes as $theme): ?>
                <li class="themes-item" id="<?= $theme->id ?>">
                    <span class="themes-item-name">
                        <?= Html::encode("{$theme->name}") ?>
                    </span>
                    <?php
                        if ($theme->closed) {
                            echo '<img class="img-closed" src="/imgs/closed.png">';

                            if ($open) {
                                echo '<img class="img-open" src="/imgs/open.png">';
                            }
                        }
                        elseif ($close) {
                            echo '<img class="img-close" src="/imgs/close.png">';
                        }
                    ?>
                </li>
            <?php endforeach; ?>
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </ul>
    </div>
</div>