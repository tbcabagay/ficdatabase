<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%faculty}}".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property integer $designation_id
 * @property string $email
 * @property string $password
 * @property string $birthday
 * @property string $tin_number
 * @property string $nationality
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Education[] $educations
 * @property Designation $designation
 * @property Facultycourse[] $facultycourses
 * @property Notice[] $notices
 */
class Faculty extends \yii\db\ActiveRecord
{
    private static $_listFaculty;

    public $confirm_password;

    const SCENARIO_SITE_CREATE = 'site_create';
    const STATUS_NEW = 10;
    const STATUS_ACTIVE = 20;
    const STATUS_DELETE = 30;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SITE_CREATE] = ['first_name', 'middle_name', 'last_name', 'designation_id', 'email', 'password', 'confirm_password', 'birthday', 'tin_number', 'nationality'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%faculty}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_key', 'first_name', 'last_name', 'middle_name', 'designation_id', 'email', 'password', 'birthday', 'tin_number', 'nationality', 'status'], 'required'],
            [['designation_id', 'status'], 'integer'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => 'Passwords don\'t match', 'on' => self::SCENARIO_SITE_CREATE],
            [['email'], 'email'],
            [['email'], 'unique'],
            /*[['email'], 'validateEmailDomain'],*/
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name', 'middle_name', 'tin_number'], 'string', 'max' => 50],
            [['email', 'nationality'], 'string', 'max' => 150],
            [['password'], 'string', 'max' => 60],
            [['designation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Designation::className(), 'targetAttribute' => ['designation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'designation_id' => Yii::t('app', 'Designation'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'birthday' => Yii::t('app', 'Birthday'),
            'tin_number' => Yii::t('app', 'TIN Number'),
            'nationality' => Yii::t('app', 'Nationality'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'plain_password' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Confirm Password'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducations()
    {
        return $this->hasMany(Education::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignation()
    {
        return $this->hasOne(Designation::className(), ['id' => 'designation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacultycourses()
    {
        return $this->hasMany(Facultycourse::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotices()
    {
        return $this->hasMany(Notice::className(), ['faculty_id' => 'id']);
    }

    public function validateEmailDomain($attribute, $params)
    {
        list($user, $domain) = split('@', $this->$attribute);
        if ($domain !== Yii::$app->params['allowedDomain']) {
            $this->addError($attribute, Yii::t('app', 'The email must be {allowedDomain} only.', ['allowedDomain' => Yii::$app->params['allowedDomain']]));
        }
    }

    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getDesignationName()
    {
        return $this->designation->abbreviation . ' ' . $this->getName();
    }

    public function add()
    {
        if ($this->isNewRecord) {
            $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $this->status = self::STATUS_NEW;
            $this->created_at = new Expression('NOW()');

            if ($this->save(false)) {
                return true;
            }
        }
        return false;
    }

    public static function getListFaculty()
    {
        self::$_listFaculty = ArrayHelper::map(self::find()->all(),
            'id',
            function($model, $defaultValue) {
                return $model->name;
            }
        );
        return self::$_listFaculty;
    }

    public function confirmEmail()
    {
        $absoluteConfirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm', 'id' => $this->id, 'code' => $this->auth_key]);
        $body = '<p>Hello ' . $this->designationName . ',</p>';
        $body .= '<p>In order to complete your registration, please click the link below</p>';
        $body .= '<p>' . Html::a($absoluteConfirmLink, $absoluteConfirmLink) . '</p>';
        Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('FIC Database Email Confirmation')
            ->setHtmlBody($body)
            ->send();
    }
}
