<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Add Courses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faculty'), 'url' => ['faculty/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?><br>
            <small><i class="glyphicon glyphicon-user"></i> <?= $faculty->name ?></small></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Search
                </div>
                <div class="panel-body">
                    <div class="col-lg-6">
                        <div class="course-search">
                            <?php $form = ActiveForm::begin([
                                'action' => ['bulk-create', 'id' => $faculty->id],
                                'method' => 'get',
                            ]); ?>

                            <?= $form->field($searchModel, 'program_id')->widget(Select2::classname(), [
                                'data' => $programs,
                                'options' => ['placeholder' => 'Select a program ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]) ?>

                            <?= $form->field($searchModel, 'code') ?>

                            <?= $form->field($searchModel, 'title') ?>

                            <div class="form-group">
                                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <?=Html::beginForm(['bulk-create', 'id' => $faculty->id],'post');?>
                <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $column) use ($assignedCourses) {
                                $bool = in_array($model->id, $assignedCourses);
                                return ['checked' => $bool];
                            },
                        ],
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'program_id',
                            'value' => 'program.code',
                        ],
                        'code',
                        'title',
                    ],
                    'pjax' => true,
                    'toolbar' => [
                        ['content' =>
                            Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Go Back', ['faculty/index'], ['data-pjax' => 0, 'class' => 'btn btn-primary', 'title' => Yii::t('app', 'Go Back')]) . ' ' .
                            Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) . ' ' .
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['bulk-create', 'id' => $faculty->id], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
                        ],
                        '{toggleData}',
                    ],
                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY,
                        'heading' => 'Course Grid'
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
            <?= Html::endForm();?> 
        </div>
    </div>

</div>
