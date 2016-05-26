<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Storage */

$this->title = Yii::t('app', 'Create Storage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Storages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="storage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
