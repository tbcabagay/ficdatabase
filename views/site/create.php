<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Create an account';
?>
<div class="site-login">
    <div class="col-md-8 col-md-offset-2">
        <div class="login-panel panel panel-primary">
            <div class="panel-heading clearfix">
                <h3 class=" panel-title">Create an account</h3>
            </div>
            <div class="panel-body">
                <div class="clearfix">
                    <p class="pull-right"><?= Html::a('<i class="glyphicon glyphicon-home"></i> Go home', ['login']) ?></p>
                </div>

                <?php $form = ActiveForm::begin(); ?>

                <fieldset>
                    <legend>Personal Information</legend>
                    
                    <p>Please fill all fields below</p>

                    <?= $form->field($model, 'designation_id')->widget(Select2::classname(), [
                        'data' => $designations,
                        'options' => ['placeholder' => 'Select a designation ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>

                    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
              
                    <?= $form->field($model, 'birthday')->textInput() ?>
                 
                    <?= $form->field($model, 'tin_number')->textInput(['maxlength' => true]) ?>
                 
                    <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>
                </fieldset>

                <fieldset>
                    <legend>Account Information</legend>

                    <p>If you do not have <strong>UPOU webmail account</strong> or prefer to use other email address, please fill the <kbd>Password</kbd> field below </p>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                </fieldset>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
