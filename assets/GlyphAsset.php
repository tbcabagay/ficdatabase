<?php

namespace app\assets;

use yii\web\AssetBundle;

class GlyphAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lumino';
    public $js = [
        'js/lumino.glyphs.js',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];
}