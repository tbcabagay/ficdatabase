<?php

namespace app\assets;

use yii\web\AssetBundle;

class LteIE9Asset extends AssetBundle
{
    public $sourcePath = '@vendor/lumino';
    public $js = [
        'js/html5shiv.min.js',
        'respond.mins.js',
    ];
    public $jsOptions = [
        'condition' => 'lte IE9',
        'position' => \yii\web\View::POS_HEAD,
    ];
}
