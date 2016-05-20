<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%faculty}}".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property integer $designation_id
 * @property string $email
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Designation $designation
 * @property Facultycourse[] $facultycourses
 * @property Notice[] $notices
 */
class Faculty extends \yii\db\ActiveRecord
{
    private static $_listFaculty;

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
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'middle_name', 'designation_id', 'email', 'status', 'created_at'], 'required'],
            [['designation_id', 'status'], 'integer'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['email'], 'validateEmailDomain'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 150],
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
            'designation_id' => Yii::t('app', 'Designation ID'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
        self::$_listFaculty = ArrayHelper::map(self::find()->all(), 'id', 'last_name');
        return self::$_listFaculty;
    }
}
