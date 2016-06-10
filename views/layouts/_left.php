<?php

use yii\bootstrap\Nav;
use app\models\User;

$identity = \Yii::$app->user->identity;
?>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <?= Nav::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Faculty',
                'url' => ['/main/faculty/index'],
                'visible' => $identity->role === User::ROLE_USER,
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Program',
                'url' => ['/main/program/index'],
                'visible' => $identity->role === User::ROLE_USER,
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Course',
                'url' => ['/main/course/index'],
                'visible' => $identity->role === User::ROLE_USER,
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Designation',
                'url' => ['/main/designation/index'],
                'visible' => $identity->role === User::ROLE_USER,
            ],
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Template',
                'url' => ['/main/template/index'],
                'visible' => $identity->role === User::ROLE_USER,
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> User',
                'url' => ['/main/user/index'],
                'visible' => $identity->role === User::ROLE_USER,
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Office',
                'url' => ['/main/office/index'],
                'visible' => $identity->role === User::ROLE_USER,
            ],

        ],
        'options' => ['class' => 'menu'],
    ]);
    ?>
</div>