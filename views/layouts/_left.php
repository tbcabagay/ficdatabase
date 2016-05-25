<?php

use yii\bootstrap\Nav;
?>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <?= Nav::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Faculty',
                'url' => ['/main/faculty/index'],
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Program',
                'url' => ['/main/program/index'],
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Course',
                'url' => ['/main/course/index'],
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Designation',
                'url' => ['/main/designation/index'],
            ],
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Template',
                'url' => ['/main/template/index'],
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> User',
                'url' => ['/main/user/index'],
            ],
            [
                'label' => '<svg class="glyph stroked chevron right"><use xlink:href="#stroked-chevron-right"/></svg> Office',
                'url' => ['/main/office/index'],
            ],

        ],
        'options' => ['class' => 'menu'],
    ]);
    ?>
</div>