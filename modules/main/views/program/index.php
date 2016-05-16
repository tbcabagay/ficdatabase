<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\models\Office;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Programs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-index">

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

                    [
                        'attribute' => 'office_id',
                        'value' => 'office.code',
                        'filter' => Office::getListOffice(),
                    ],
                    'code',
                    'name',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
                'pjax' => true,
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'],['title' => Yii::t('app', 'Add'), 'class' => 'btn btn-success',]) . ' '.
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
                    ],
                    '{toggleData}',
                ],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => 'Program Grid'
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
