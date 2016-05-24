<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\models\User;
use app\models\Office;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

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

                    'email:email',
                    [
                        'attribute' => 'office_id',
                        'value' => 'office.code',
                        'filter' => Office::getListOffice(),
                    ],
                    [
                        'hAlign' => 'center',
                        'format' => 'html',
                        'attribute' => 'status',
                        'value' => function ($model, $key, $index, $column) {
                            $display = '';
                            if ($model->status === User::STATUS_ACTIVE) {
                                $display = '<span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span>';
                            } else if ($model->status === User::STATUS_DELETE) {
                                $display = '<span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>';
                            }
                            return $display;
                        },
                        'filter' => User::getListStatus(),
                    ],
                    [
                        'attribute' => 'created_at',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'updated_at',
                        'filter' => false,
                    ],

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
                    'heading' => 'User Grid'
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
