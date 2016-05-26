<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Storage */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Storage',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Storages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="storage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
