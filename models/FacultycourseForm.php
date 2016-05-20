<?php

namespace app\models;

use Yii;
use yii\base\Model;

class FacultycourseForm extends Model
{
    public $courses;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['courses'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'courses' => 'Available Courses',
        ];
    }

    public function add($faculty_id)
    {
        if ($this->validate()) {
            $result = true;

            $this->deleteCourse($faculty_id);

            foreach ($this->courses as $course) {
                $model = new Facultycourse([
                    'faculty_id' => $faculty_id,
                    'course_id' => $course,
                ]);
                
                $result = $result && $model->save();
            }
            if ($result) {
                return true;
            }
        }
        return false;
    }

    public function deleteCourse($faculty_id)
    {
        Facultycourse::deleteAll(['faculty_id' => $faculty_id]);
    }
    
    public function getAssignedCourses($faculty_id)
    {
        $courses = [];
        $courses = Facultycourse::find()->where(['faculty_id' => $faculty_id])->all();
        return $courses;
    }
}
