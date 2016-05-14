<?php

use yii\bootstrap\Nav;
?>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <?= Nav::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => '<svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg>User',
                'url' => ['/main/user/index'],
            ],
            [
                'label' => '<svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg>Office',
                'url' => ['/main/office/index'],
            ],
        ],
        'options' => ['class' => 'menu'],
    ]);
    ?>
</div>