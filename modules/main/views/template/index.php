<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Templates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    [
                        'attribute' => 'content',
                        'value' => function($model, $key, $index, $column) {
                            return Html::a('<i class="glyphicon glyphicon-save"></i> Download', ['download', 'id' => $model->id], ['data-pjax' => 0, 'target' => '_blank', 'class' => 'btn btn-primary btn-xs']);
                        },
                        'format' => 'raw',
                        'hAlign' => 'center',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => 'user.email',
                        'format' => 'email',
                        'label' => 'Uploaded by',
                    ],
                    'created_at',
                    'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
                'pjax' => true,
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'],['data-pjax' => 0, 'title' => Yii::t('app', 'Add'), 'class' => 'btn btn-success',]) . ' '.
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
                    ],
                    '{toggleData}',
                ],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => 'Office Grid'
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
