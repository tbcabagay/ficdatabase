<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $faculty_id
 * @property integer $template_id
 * @property integer $course_id
 * @property string $reference_number
 * @property string $semester
 * @property string $academic_year
 * @property string $date_course_start
 * @property string $date_final_exam
 * @property string $date_submission
 * @property string $created_at
 *
 * @property Course $course
 * @property Faculty $faculty
 * @property Template $template
 * @property User $user
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'faculty_id', 'template_id', 'course_id', 'reference_number', 'semester', 'academic_year', 'date_course_start', 'date_final_exam', 'date_submission', 'created_at'], 'required'],
            [['user_id', 'faculty_id', 'template_id', 'course_id'], 'integer'],
            [['date_course_start', 'date_final_exam', 'date_submission', 'created_at'], 'safe'],
            [['reference_number'], 'string', 'max' => 7],
            [['semester'], 'string', 'max' => 1],
            [['academic_year'], 'string', 'max' => 9],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faculty::className(), 'targetAttribute' => ['faculty_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['template_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'faculty_id' => Yii::t('app', 'Faculty ID'),
            'template_id' => Yii::t('app', 'Template ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'reference_number' => Yii::t('app', 'Reference Number'),
            'semester' => Yii::t('app', 'Semester'),
            'academic_year' => Yii::t('app', 'Academic Year'),
            'date_course_start' => Yii::t('app', 'Date Course Start'),
            'date_final_exam' => Yii::t('app', 'Date Final Exam'),
            'date_submission' => Yii::t('app', 'Date Submission'),
            'created_at' => Yii::t('app', 'Created At'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function add()
    {
        if ($this->isNewRecord) {
            return true;
        }
        return false;
    }
}
