<?php

namespace app\modules\main\controllers;

use Yii;
use app\models\Facultycourse;
use app\models\FacultycourseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\Faculty;
use app\models\Course;
use app\models\FacultycourseForm;
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Creates a new Facultycourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $faculty = Faculty::findOne($id);
        if ($faculty !== null) {
            $model = new FacultycourseForm();
            $model->courses = $model->getAssignedCourses($id);

            if ($model->load(Yii::$app->request->post()) && $model->add($id)) {
                Yii::$app->session->setFlash('success', [
                    Yii::t('app', 'The selected courses has been saved.'),
                ]);

                return $this->redirect(['create', 'id' => $id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'faculty' => $faculty,
                    'courses' => Course::getListMultipleCourse(),
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
