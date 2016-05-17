<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Course;

class CourseController extends Controller
{
    public function actionIndex($program)
    {
        $program = strtolower($program);
        $course['man'] = [
            [1, 'N204', 'Advanced Pathophysiology'],
            [1, 'N207', 'Theoretical Foundations of Nursing'],
            [1, 'N298', 'Statistical Methods in Nursing'],
            [1, 'N299', 'Research Methods in Nursing'],
            [1, 'N260', 'Advanced Adult Health Nursing'],
            [1, 'N261', 'Nursing Care in Cardiovascular Conditions'],
            [1, 'N262', 'Oncology Nursing'],
            [1, 'N219.6', 'Intensive Experience in Adult Health Nursing'],
            [1, 'N230', 'Primary Care of Women'],
            [1, 'N231', 'Perinatal Nursing'],
            [1, 'N232', 'Nursing Care of Children'],
            [1, 'N219.3', 'Intensive Experience in Maternal-Child Nursing'],
            [1, 'N240', 'Concepts and Principles in Nursing Administration'],
            [1, 'N241', 'Human Resource Management in Health'],
            [1, 'N242', 'Organizational Development in Nursing'],
            [1, 'N219.4', 'Intensive Practicum in Nursing Administration'],
            [1, 'N280', 'Geriatrics and Gerontology Nursing'],
            [1, 'N281', 'Promoting Health and Wellness in Older People'],
            [1, 'N282', 'Nursing Care of Older People'],
            [1, 'N219.8', 'Intensive Clinical Practicum in Gerontology and Geriatric Nursing'],
        ];
        $course['menrm'] = [
            [2, 'ENRM223', 'Ecosystem Structure and Dynamics'],
            [2, 'ENRM221', 'Socio-Cultural Perspectives on the Environment'],
            [2, 'ENRM230', 'Principles and Applications of Landscape Ecology'],
            [2, 'ENRM231', 'Economics of Upland Resources'],
            [2, 'SF263', 'Cultures and Societies in Tropical Ecosystems'],
            [2, 'ENRM236', 'Governance of the Upland Environment'],
            [2, 'ENRM240', 'Aquatic Ecosystems'],
            [2, 'ENRM241', 'Economic Valuation and Assessment of Aquatic Resources'],
            [2, 'ENRM244', 'Coastal Anthropology'],
            [2, 'ENRM246', 'Governance of the Coastal Environment'],
            [2, 'ENRM232', 'Management of Terrestrial Protected Areas'],
        ];
        if (isset($course[$program])) {
            echo "Executing batch insert\n";
            \Yii::$app->db->createCommand()->batchInsert(Course::tableName(), ['program_id', 'code', 'title'], $course[$program])->execute();
            echo "done...\n";
            return 0;
        } else {
            $this->stdout("Error!\n", Console::BOLD);
            $code = $this->ansiFormat('php yii program <code>', Console::FG_RED);
            echo "Run $code\nwhere <code> is one of the following values: [man|menrm|mih|mlvm|mpm|msw]\n";
            return 1;
        }        
    }
}
