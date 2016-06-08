<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\growl\Growl;

$this->title = 'Login';
$session = Yii::$app->getSession();
?>
<div class="site-login">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Log in</h3>
            </div>
            <div class="panel-body">

                <p>If you do not have UPOU webmail account or prefer to use other email address, please login below</p>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Go'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <hr>
                <p>... or click the button to login using UPOU Google account</p>

                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['site/auth'],
                    'popupMode' => false,
                ]) ?>
            </div>
            <div class="panel-footer">
                <p class="text-center"><strong><?= Html::a('<i class="glyphicon glyphicon-info-sign"></i> Create an account', ['create']) ?></strong></p>
            </div>
        </div>
    </div>
</div>

<?php if ($session->has('error')) {
    echo Growl::widget([
        'type' => Growl::TYPE_GROWL,
        'title' => 'Error!',
        'body' => $session->getFlash('error'),
    ]);
} ?>

<?php if ($session->has('confirm_success')) {
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'icon' => 'glyphicon glyphicon-ok-sign',
        'title' => 'Success!',
        'body' => $session->getFlash('confirm_success'),
    ]);
} ?>

<?php if ($session->has('confirm_error')) {
    echo Growl::widget([
        'type' => Growl::TYPE_DANGER,
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'title' => 'Error!',
        'body' => $session->getFlash('confirm_error'),
    ]);
} ?>