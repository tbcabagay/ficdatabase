<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Facultycourse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="facultycourse-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'courses')->dropDownList($courses, ['multiple' => true, 'size' => 30]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
