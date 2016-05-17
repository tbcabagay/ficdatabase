<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Designation;

class DesignationController extends Controller
{
    public function actionIndex()
    {
        $designation = [
            ['Assoc. Prof.', 'Associate Professor'],
            ['Prof.', 'Professor'],
        ];
        echo "Executing batch insert\n";
        \Yii::$app->db->createCommand()->batchInsert(Designation::tableName(), ['abbreviation', 'title'], $designation)->execute();
        echo "done...\n";
        return 0;
    }
}
