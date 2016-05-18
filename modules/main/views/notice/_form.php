<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Notice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'template_id')->widget(Select2::classname(), [
        'data' => $templates,
        'options' => ['placeholder' => 'Select a template ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'reference_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'semester')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'academic_year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_course_start')->widget(DatePicker::className(), [
        'pluginOptions' => [
            'autoclose' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'date_final_exam')->widget(DatePicker::className(), [
        'pluginOptions' => [
            'autoclose' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'date_submission')->widget(DatePicker::className(), [
        'pluginOptions' => [
            'autoclose' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'course_id')->multiselect($assignedCourses, ['height' => '250px']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
