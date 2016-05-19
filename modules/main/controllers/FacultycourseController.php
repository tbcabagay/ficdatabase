<?php

namespace app\modules\main\controllers;

use Yii;
use app\models\Facultycourse;
use app\models\FacultycourseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Faculty;
use app\models\Program;
use app\models\CourseSearch;

/**
 * FacultycourseController implements the CRUD actions for Facultycourse model.
 */
class FacultycourseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionBulkCreate($id)
    {
        $faculty = Faculty::findOne($id);
        if ($faculty === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $assignedCourses = Facultycourse::getColumnAssignedCourses($id);
        $programs = Program::getListProgram();

        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;

        if (Yii::$app->request->isPost) {
            $result = true;
            $selections = \Yii::$app->request->post('selection');

            Facultycourse::deleteCourse($id);

            foreach ($selections as $selection) {
                $model = new Facultycourse([
                    'faculty_id' => $id,
                    'course_id' => $selection,
                ]);
                $result = $result && $model->save();
            }
            if ($result) {
                $this->refresh();
            } else {
                throw new BadRequestHttpException('There is a problem with you request. Please try again');
            }
        }

        return $this->render('bulk-create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'assignedCourses' => $assignedCourses,
            'faculty' => $faculty,
            'programs' => $programs,
        ]);
    }
}
