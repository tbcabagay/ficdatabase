<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Office;

class OfficeController extends Controller
{
    public function actionIndex()
    {
        $office = [
            ['FMDS', 'Faculty of Management and Development Studies'],
        ];
        echo "Executing batch insert\n";
        \Yii::$app->db->createCommand()->batchInsert(Office::tableName(), ['code', 'name'], $office)->execute();
        echo "done...\n";
        return 0;
    }
}
