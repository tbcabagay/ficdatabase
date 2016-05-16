<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Facultycourse */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Courses',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Facultycourses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="facultycourse-update">

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
                            'faculties' => $faculties,
                            'courses' => $courses,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
