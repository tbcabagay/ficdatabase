<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\LuminoAsset;
use app\assets\LteIE9Asset;

LuminoAsset::register($this);
LteIE9Asset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        html {
            background: url('/images/background.jpg') no-repeat center center fixed; 
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        html:before{
            position: absolute;
            content:" ";
            top:0;
            left:0;
            width:100%;
            height:100%;
            display: block;
            z-index:0;
            background-color: rgba(0, 0 ,0 ,0.5);
        }
        body {
            background-color: transparent;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
