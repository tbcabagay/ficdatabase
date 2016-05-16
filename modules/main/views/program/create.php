<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Program */

$this->title = Yii::t('app', 'Create Program');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-create">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-6">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'offices' => $offices,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
