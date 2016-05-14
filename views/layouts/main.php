<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\LuminoAsset;
use app\assets\GlyphAsset;
use app\assets\LteIE9Asset;

LuminoAsset::register($this);
GlyphAsset::register($this);
LteIE9Asset::register($this);

$this->registerJs("
    !function ($) {
        $(window).on('resize', function () {
            if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
        });
        $(window).on('resize', function () {
            if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
        });
    } (window.jQuery);
");
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
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('_top') ?>

<?= $this->render('_left') ?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <?= Breadcrumbs::widget([
            'encodeLabels' => false,
            'homeLink' => [
                'label' => '<svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg>',
                'url' => Yii::$app->homeUrl,
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>

    <?= $content; ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
