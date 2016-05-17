<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Faculty;
use yii\db\Expression;

class FacultyController extends Controller
{
    public function actionIndex()
    {
        $faculty = [
            ['Tomas Jr', 'Cabagay', 'Bardenas', rand(1,2), 'tomasjr.cabagay@upou.edu.ph', Faculty::STATUS_ACTIVE, new Expression('NOW()')],
            ['Noreen Dianne', 'Alazada', 'Sanga', rand(1,2), 'noreendianne.alazada@upou.edu.ph', Faculty::STATUS_ACTIVE, new Expression('NOW()')],
        ];
        echo "Executing batch insert\n";
        \Yii::$app->db->createCommand()->batchInsert(Faculty::tableName(), ['first_name', 'last_name', 'middle_name', 'designation_id', 'email', 'status', 'created_at'], $faculty)->execute();
        echo "done...\n";
        return 0;
    }
}
