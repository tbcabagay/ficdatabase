<?php

use yii\helpers\Html;
$identity = Yii::$app->user->identity;
?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?= Html::a('<span>UPOU</span>FIC Database', ['/main/default/index'], ['class' => 'navbar-brand']) ?>
            <ul class="user-menu">
                <li class="dropdown pull-right">
                <?php if (\Yii::$app->user->isGuest === false): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> <?= Html::encode($identity->email) ?> <span class="caret"></span></a>
                <?php endif; ?>
                    <ul class="dropdown-menu" role="menu">
                        <li><?= Html::a('<svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Profile', '#') ?></li>
                        <li><?= Html::a('<svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Logout', ['/site/logout'], ['data-method' => 'post']) ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>