<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Program;

class ProgramController extends Controller
{
    public function actionIndex($office)
    {
        $office = strtolower($office);
        $program['fmds'] = [
            [1, 'MAN', 'Master of Arts in Nursing'],
            [1, 'MENRM', 'Master of Environment and Natural Resources Management'],
            [1, 'MIH', 'Master of International Health'],
            [1, 'MLVM', 'Master of Land Valuation and Management'],
            [1, 'MPM', 'Master of Public Management'],
            [1, 'MSW', 'Master of Social Work'],
        ];
        if (isset($program[$office])) {
            echo "Executing batch insert\n";
            \Yii::$app->db->createCommand()->batchInsert(Program::tableName(), ['office_id', 'code', 'name'], $program[$office])->execute();
            echo "done...\n";
            return 0;
        } else {
            $this->stdout("Error!\n", Console::BOLD);
            $code = $this->ansiFormat('php yii program <code>', Console::FG_RED);
            echo "Run $code\nwhere <code> is one of the following values: [fmds|fed|fics]\n";
            return 1;
        }        
    }
}
