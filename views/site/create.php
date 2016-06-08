<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\growl\Growl;

$this->title = 'Create an account';

$session = Yii::$app->getSession();
?>
<div class="site-login">
    <div class="col-md-12">
        <div class="login-panel panel panel-primary">
            <div class="panel-heading clearfix">
                <h3 class=" panel-title">Create an account</h3>
            </div>
            <div class="panel-body">
                <div class="clearfix">
                    <p class="pull-right"><?= Html::a('<i class="glyphicon glyphicon-home"></i> Go home', ['login']) ?></p>
                </div>

                <p>* Please fill all fields below</p>

                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'enableClientValidation' => false,
                ]); ?>

                <fieldset>
                    <legend>Personal Information</legend>
                    
                    <?= $form->field($faculty, 'designation_id')->widget(Select2::classname(), [
                        'data' => $designations,
                        'options' => ['placeholder' => 'Select a designation ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>

                    <?= $form->field($faculty, 'first_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($faculty, 'middle_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($faculty, 'last_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($faculty, 'birthday')->widget(DatePicker::className(), [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                        ],
                    ]) ?>
                 
                    <?= $form->field($faculty, 'tin_number')->textInput(['maxlength' => true]) ?>
                 
                    <?= $form->field($faculty, 'nationality')->textInput(['maxlength' => true]) ?>
                </fieldset>

                <fieldset>
                    <legend>Educational Background</legend>

                    <?= $form->field($education, 'degree')->textInput(['maxlength' => true])->label('Highest Degree Attained') ?>

                    <?= $form->field($education, 'school')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($education, 'date_graduate')->widget(DatePicker::className(), [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy',
                            'todayHighlight' => true,
                            'viewMode' => 'years',
                            'minViewMode' => 'years',
                        ],
                    ])->label('Year of Graduation') ?>
                </fieldset>

                <fieldset>
                    <legend>Account Information</legend>

                    <?= $form->field($faculty, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($faculty, 'password')->passwordInput(['maxlength' => true]) ?>

                    <?= $form->field($faculty, 'confirm_password')->passwordInput(['maxlength' => true]) ?>
                </fieldset>

                <div class="form-group">
                    <div class="col-sm-offset-10 col-sm-2">
                        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
                        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php if ($session->has('success')) {
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'icon' => 'glyphicon glyphicon-ok-sign',
        'title' => 'Success!',
        'body' => $session->getFlash('success'),
    ]);
} ?>