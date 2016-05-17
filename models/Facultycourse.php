<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%facultycourse}}".
 *
 * @property integer $id
 * @property integer $faculty_id
 * @property integer $course_id
 *
 * @property Course $course
 * @property Faculty $faculty
 */
class Facultycourse extends \yii\db\ActiveRecord
{
    private static $_assignedCourse;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%facultycourse}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faculty_id', 'course_id'], 'required'],
            [['faculty_id', 'course_id'], 'integer'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faculty::className(), 'targetAttribute' => ['faculty_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => Yii::t('app', 'ID'),
            'faculty_id' => Yii::t('app', 'Faculty ID'),
            'course_id' => Yii::t('app', 'Course ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaculty()
    {
        return $this->hasOne(Faculty::className(), ['id' => 'faculty_id']);
    }

    public static function getAssignedCourses($faculty_id)
    {
        $model = self::find()->select('course_id')->where(['faculty_id' => $faculty_id])->asArray()->all();
        self::$_assignedCourse = ArrayHelper::getColumn($model, 'course_id');
        return self::$_assignedCourse;
    }

    public static function deleteCourse($faculty_id)
    {
        self::deleteAll(['faculty_id' => $faculty_id]);
    }
}
