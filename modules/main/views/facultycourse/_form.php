<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Facultycourse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="facultycourse-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*$form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses,
        'options' => ['placeholder' => 'Select a course ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])*/ ?>

    <?php for ($i = 0; $i < count($courses); $i++): ?>
        <?php if (($i == 0) || ($i % 2) == 0): ?>
    <div class="row"><!-- start -->
        <?php endif; ?>
        <div class="col-lg-6">
            <fieldset>
                <legend><?= $courses[$i]['program'] ?></legend>

                <?= $form->field($model, 'course_id')->checkboxList($courses[$i]['courses'])->label(false) ?>

            </fieldset>
        </div>
        <?php if (($i == 0) || ($i % 2) == 0): ?>
    </div><!-- end -->
        <?php endif; ?>
    <?php endfor; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
