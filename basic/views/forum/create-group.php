<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forum\CreateGroup */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Group Creation';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['action' => ['forum/create-group']]); ?>

<?= $form->field($model, 'name') ?>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>