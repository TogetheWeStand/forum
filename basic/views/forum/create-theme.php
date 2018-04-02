<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forum\CreateTheme */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Theme Creation';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['action' => ['forum/create-theme', 'g_id' => $_GET['g_id']]]); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'content') ?>

    <div class="form-theme">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>