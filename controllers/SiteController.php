<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\AuthHandler;
use app\models\LoginForm;
use app\models\Faculty;
use app\models\Designation;
use app\models\Education;

class SiteController extends Controller
{
    public $layout = 'login';
    public $defaultAction = 'login';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $handle = new AuthHandler($client);
        if ($handle->handle()) {
            $this->redirect(['/main/default/index']);
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/main/default/index']);
        } else {
            $model = new LoginForm();

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/main/default/index']);
        } else {
            $faculty = new Faculty();
            $education = new Education();

            if ($faculty->load(Yii::$app->request->post()) && $education->load(Yii::$app->request->post())) {
                $transaction = $faculty->getDb()->beginTransaction();
                try {
                    if ($faculty->add()) {
                        $faculty->refresh();
                        $education->faculty_id = $faculty->id;
                        if ($education->save()) {
                            $transaction->commit();

                            Yii::$app->session->setFlash('success', [
                                Yii::t('app', 'Your account has been saved.'),
                            ]);
                            return $this->refresh();
                        }
                    }
                } catch(\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
            return $this->render('create', [
                'faculty' => $faculty,
                'education' => $education,
                'designations' => Designation::getListDesignation(),
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
