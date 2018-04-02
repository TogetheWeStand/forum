<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model  app\controllers\ForumController */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit';
$this->params['breadcrumbs']['Edit'] = 'Edit';
?>

<div class="edit-comment-header">
    <input type="hidden" name="_frontendCSRF"
           value="<?=Yii::$app->request->getCsrfToken()?>" />
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<?php $form = ActiveForm::begin(['action' => ['forum/edit-comment', 'c_id' => $_GET['c_id']]]); ?>

    <?= $form->field($model, 'content') ?>

    <div class="form-group">
        <?= Html::submitButton('Confirm', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>