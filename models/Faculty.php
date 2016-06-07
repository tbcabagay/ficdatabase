<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%faculty}}".
 *
 * @property integer $id
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

    public $plain_password;
    public $confirm_password;

    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 20;
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
            [['first_name', 'last_name', 'middle_name', 'designation_id', 'email', 'plain_password', 'birthday', 'tin_number', 'nationality', 'status'], 'required'],
            [['designation_id', 'status'], 'integer'],
            ['confirm_password', 'compare', 'compareAttribute' => 'plain_password', 'skipOnEmpty' => false, 'message' => "Passwords don't match"],
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
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->plain_password);
            $this->status = self::STATUS_ACTIVE;
            $this->created_at = new Expression('NOW()');

            if ($this->save()) {
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
}
