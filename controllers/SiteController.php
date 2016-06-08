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
use yii\web\NotFoundHttpException;

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

            $faculty->scenario = Faculty::SCENARIO_SITE_CREATE;
            $education->scenario = Faculty::SCENARIO_SITE_CREATE;

            if ($faculty->load(Yii::$app->request->post()) && $education->load(Yii::$app->request->post())) {
                $isValid = $faculty->validate();
                $isValid = $education->validate() && $isValid;
                if ($isValid) {
                    $transaction = $faculty->getDb()->beginTransaction();
                    try {
                        if ($faculty->add()) {
                            $faculty->refresh();
                            $education->faculty_id = $faculty->id;
                            if ($education->save(false)) {
                                $transaction->commit();

                                if (Yii::$app->params['confirmEmail']) {
                                    $faculty->confirmEmail();
                                    $message = Yii::t('app', 'Your account has been saved. Please check your email for confirmation link to complete your registration');
                                } else {
                                    $message = Yii::t('app', 'Your account has been saved.');
                                }

                                Yii::$app->session->setFlash('success', $message);
                                return $this->refresh();
                            }
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            }
            return $this->render('create', [
                'faculty' => $faculty,
                'education' => $education,
                'designations' => Designation::getListDesignation(),
            ]);
        }
    }

    public function actionConfirm($id, $code)
    {
        $model = Faculty::find()->where([
            'id' => $id,
            'auth_key' => $code,
            'status' => Faculty::STATUS_NEW,
        ])->one();
        if ($model !== null) {
            $model->status = Faculty::STATUS_ACTIVE;
            if ($model->save()) {
                Yii::$app->session->setFlash('confirm_success', Yii::t('app', 'You have successfully confirmed your email address. You may sign in using your credentials now.'));
            } else {
                Yii::$app->session->setFlash('confirm_error', Yii::t('app', 'There seems to be a problem activating your account. Please contact the site administrator.'));
            }
            $this->redirect(['login']);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
