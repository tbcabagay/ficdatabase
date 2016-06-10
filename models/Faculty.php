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
 * @property string $email
 * @property integer $designation_id
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
 * @property User[] $users
 */
class Faculty extends \yii\db\ActiveRecord
{
    private static $_listFaculty;

    const SCENARIO_SITE_CREATE = 'site_create';
    const STATUS_ACTIVE = 20;
    const STATUS_DELETE = 30;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SITE_CREATE] = ['first_name', 'middle_name', 'last_name', 'designation_id', 'birthday', 'tin_number', 'nationality'];
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
            [['first_name', 'last_name', 'middle_name', 'email', 'designation_id', 'birthday', 'tin_number', 'nationality', 'status'], 'required'],
            [['designation_id', 'status'], 'integer'],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name', 'middle_name', 'tin_number'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 255],
            [['nationality'], 'string', 'max' => 150],
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
            'birthday' => Yii::t('app', 'Birthday'),
            'tin_number' => Yii::t('app', 'TIN Number'),
            'nationality' => Yii::t('app', 'Nationality'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['faculty_id' => 'id']);
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
            $this->status = self::STATUS_ACTIVE;
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
}
