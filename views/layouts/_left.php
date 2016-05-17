<?php

use yii\bootstrap\Nav;
?>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <?= Nav::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => 'Program',
                'url' => ['/main/program/index'],
            ],
            [
                'label' => 'Course',
                'url' => ['/main/course/index'],
            ],
            [
                'label' => 'Designation',
                'url' => ['/main/designation/index'],
            ],
            [
                'label' => 'Faculty',
                'url' => ['/main/faculty/index'],
            ],
            '<li role="presentation" class="divider"></li>',
            [
                'label' => 'User',
                'url' => ['/main/user/index'],
            ],
            [
                'label' => 'Office',
                'url' => ['/main/office/index'],
            ],

        ],
        'options' => ['class' => 'menu'],
    ]);
    ?>
</div>