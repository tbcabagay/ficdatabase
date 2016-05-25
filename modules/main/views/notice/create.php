<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Notice */

$this->title = Yii::t('app', 'Create Notice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-create">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?><br>
            <small><i class="glyphicon glyphicon-user"></i> <?= $faculty->name ?></small>
            <?= Html::a('<span class="glyphicon glyphicon-menu-left"></span> Go Back', ['notice/index', 'faculty_id' => $faculty->id], ['class' => 'btn btn-primary pull-right']) ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-6">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'templates' => $templates,
                            'assignedCourses' => $assignedCourses,
                            'semesters' => $semesters,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
