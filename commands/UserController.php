<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\User;
use yii\db\Expression;

class UserController extends Controller
{
    public function actionIndex()
    {
        $user = [
            [\Yii::$app->security->generateRandomString(), 'tomasjr.cabagay@upou.edu.ph', User::STATUS_ACTIVE, 1, new Expression('NOW()')],
            [\Yii::$app->security->generateRandomString(), 'noreendianne.alazada@upou.edu.ph', User::STATUS_ACTIVE, 1, new Expression('NOW()')],
        ];
        echo "Executing batch insert\n";
        \Yii::$app->db->createCommand()->batchInsert(User::tableName(), ['auth_key', 'email', 'status', 'office_id', 'created_at'], $user)->execute();
        echo "done...\n";
        return 0;
    }
}
