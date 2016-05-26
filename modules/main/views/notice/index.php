<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\models\Notice;
/* @var $this yii\web\View */
/* @var $searchModel app\models\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?><br>
            <small><i class="glyphicon glyphicon-user"></i> <?= $faculty->name ?></small>
            <?= Html::a('<span class="glyphicon glyphicon-menu-left"></span> Go Back', ['faculty/index'], ['class' => 'btn btn-primary pull-right']) ?></h1></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'reference_number',
                        'value' => 'notice.reference_number',
                    ],
                    [
                        'attribute' => 'course_id',
                        'value' => function($model, $key, $index, $column) {
                            return $model->course->code . ' - ' . $model->course->title;
                        },
                    ],
                    [
                        'attribute' => 'location',
                        'hAlign' => 'center',
                        'value' => function($model, $key, $index, $column) {
                            return Html::a('<i class="glyphicon glyphicon-save"></i>', ['download', 'storage_id' => $model->id], ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                        'label' => 'Download',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function($model, $key, $index, $column) {
                            return Yii::$app->formatter->asDateTime($model->created_at);
                        },
                    ],

                    /*['class' => 'yii\grid\ActionColumn'],*/
                ],
                'pjax' => true,
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create', 'faculty_id' => $faculty->id],['data-pjax' => 0, 'title' => Yii::t('app', 'Add'), 'class' => 'btn btn-success',]) . ' '.
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])],
                    '{toggleData}',
                ],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => 'Course Grid'
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
