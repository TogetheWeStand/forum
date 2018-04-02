<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forum\Login */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>Благодарим за регистрацию!</p>
<p>Теперь вы можете войти на форум используя данные введённые при регистрации</p>

<?php $form = ActiveForm::begin(['action' =>['forum/login']]); ?>

<?= $form->field($model, 'mail') ?>
<?= $form->field($model, 'pass')->input('password') ?>

    <div class="form-group">
        <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>