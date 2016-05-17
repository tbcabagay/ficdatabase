<?php

namespace app\assets;

use yii\web\AssetBundle;

class LuminoAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lumino';
    public $css = [
        'css/styles.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
