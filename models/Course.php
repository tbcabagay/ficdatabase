<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%course}}".
 *
 * @property integer $id
 * @property integer $program_id
 * @property string $code
 * @property string $title
 *
 * @property Program $program
 * @property Facultycourse[] $facultycourses
 */
class Course extends \yii\db\ActiveRecord
{
    private static $_listCourse;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'code', 'title'], 'required'],
            [['program_id'], 'integer'],
            [['code'], 'unique'],
            [['code'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 150],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'program_id' => Yii::t('app', 'Program ID'),
            'code' => Yii::t('app', 'Code'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacultycourses()
    {
        return $this->hasMany(Facultycourse::className(), ['course_id' => 'id']);
    }

    public static function getListCourse()
    {
        self::$_listCourse = ArrayHelper::map(self::find()->all(), 'id', 'code');
        return self::$_listCourse;
    }

    public static function getCheckboxListCourse()
    {
        $courses = [];
        $programs = Program::find()->select(['id', 'name', 'code'])->asArray()->all();

        foreach ($programs as $program) {
            $courses = ArrayHelper::map(self::find()->where(['program_id' => $program['id']])->all(),
                'id',
                function($model, $defaultValue) {
                    return $model->code . ' - ' . $model->title;
                }
            );
            if (!empty($courses)) {
                self::$_listCourse[] = [
                    'program' => $program['name'] . ' (' . $program['code'] . ')',
                    'courses' => $courses,
                ];
            }
        }
        return self::$_listCourse;
    }
}
