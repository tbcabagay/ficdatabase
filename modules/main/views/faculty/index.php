<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\FacultySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Faculties');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    !function ($) {
        var addCourses = $('#add-courses');
        var grid = $('#w0');
        var url = '" . Url::to(['/main/facultycourse/create']) . "';
        addCourses.hide();
        
        grid.on('grid.radiochecked', function(ev, key, val) {
            addCourses.show();
            addCourses.attr('href', url + '?id=' + val);
        });
        grid.on('grid.radiocleared', function(ev, key, val) {
            addCourses.hide();
        });
    } (window.jQuery);
");
?>
<div class="faculty-index">

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
                        'class' => '\kartik\grid\RadioColumn',
                        'vAlign' => 'top',
                    ],

                    'first_name',
                    'last_name',
                    'middle_name',
                    'email:email',
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
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'],['title' => Yii::t('app', 'Add'), 'class' => 'btn btn-success',]) . ' '.
                        Html::a('<i class="glyphicon glyphicon-book"></i> Courses', '#',['title' => Yii::t('app', 'Add'), 'class' => 'btn btn-primary', 'id' => 'add-courses']) . ' '.
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
                    ],
                    '{toggleData}',
                ],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => 'Designation Grid'
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        </div>
    </div>

</div>
