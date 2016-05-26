<?php

namespace app\modules\main\controllers;

use Yii;
use app\models\Notice;
use app\models\NoticeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\Faculty;
use app\models\Facultycourse;
use app\models\Template;
use app\models\Storage;
use app\models\StorageSearch;

/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class NoticeController extends Controller
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
     * Lists all Notice models.
     * @return mixed
     */
    public function actionIndex($faculty_id)
    {
        $faculty = Faculty::findOne($faculty_id);
        if ($faculty !== null) {
            $searchModel = new StorageSearch();
            $dataProvider = $searchModel->search($faculty_id, Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'faculty' => $faculty,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Displays a single Notice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Notice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($faculty_id)
    {
        $faculty = Faculty::findOne($faculty_id);
        if ($faculty === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = new Notice();
        $model->generateReferenceNumber();

        $model->template_id = 1;
        $model->semester = 1;
        $model->academic_year = '2015-2016';
        $model->date_course_start = '2016-05-25';
        $model->date_final_exam = '2016-05-26';
        $model->date_submission = '2016-05-27';

        if ($model->load(Yii::$app->request->post()) && $model->add($faculty_id)) {
            return $this->redirect(['index', 'faculty_id' => $faculty->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'faculty' => $faculty,
                'templates' => Template::getListTemplate(),
                'assignedCourses' => Facultycourse::getListAssignedCourses($faculty_id),
                'semesters' => $model->getSemesterRadioList(),
            ]);
        }
    }

    /**
     * Updates an existing Notice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Notice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDownload($storage_id)
    {
        $model = Storage::findOne($storage_id);
        if ($model !== null) {
            return \Yii::$app->response->sendFile($model->location);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Notice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
