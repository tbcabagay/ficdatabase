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
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Log in using UPOU Google account</div>
            <div class="panel-body">
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['site/auth'],
                    'popupMode' => false,
                ]) ?>
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