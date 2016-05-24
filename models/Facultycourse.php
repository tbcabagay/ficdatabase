<?php

namespace app\models;

use Yii;

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

    public static function primaryKey()
    {
        return ['course_id'];
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

    /*public static function getColumnAssignedCourses($faculty_id)
    {
        $model = self::find()->select('course_id')->where(['faculty_id' => $faculty_id])->asArray()->all();
        self::$_assignedCourse = ArrayHelper::getColumn($model, 'course_id');
        return self::$_assignedCourse;
    }*/

    public static function getListAssignedCourses($faculty_id)
    {
        self::$_assignedCourse = [];
        $courses = [];

        $courses = self::find()->joinWith('course')->where(['faculty_id' => $faculty_id])->orderBy('course.title ASC')->all();
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $programId = $course->course->program->name;
                $courseId = $course->course->id;
                $courseTitle = $course->course->code . ' - ' . $course->course->title;
                if (!isset(self::$_assignedCourse[$programId])) {
                    self::$_assignedCourse[$programId] = [];
                }
                if (!isset(self::$_assignedCourse[$programId][$courseId])) {
                    self::$_assignedCourse[$programId][$courseId] = $courseTitle;
                }
            }
        }
        return self::$_assignedCourse;
    }

    /*public static function deleteCourse($faculty_id)
    {
        self::deleteAll(['faculty_id' => $faculty_id]);
    }*/
}
