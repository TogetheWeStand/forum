<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forum\CreateTheme */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Comment Adding';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['action' => ['forum/add-comment', 'id' => $_GET['id']]]); ?>

    <?= $form->field($model, 'content') ?>

    <div class="form-comment">
        <?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>