<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use kartik\mpdf\Pdf;

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
 * @property integer $status
 * @property string $location
 * @property string $created_at
 *
 * @property Course $course
 * @property Faculty $faculty
 * @property Template $template
 * @property User $user
 */
class Notice extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 10;

    public $courses;
    private $_filename = null;
    private $_path = null;
    private $_course = null;
    private $_faculty = null;
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
            [['user_id', 'faculty_id', 'template_id', 'course_id', 'reference_number', 'semester', 'academic_year', 'date_course_start', 'date_final_exam', 'date_submission', 'status', 'location', 'created_at', 'courses'], 'required'],
            [['user_id', 'faculty_id', 'template_id', 'course_id', 'status'], 'integer'],
            [['date_course_start', 'date_final_exam', 'date_submission', 'created_at'], 'safe'],
            [['reference_number'], 'string', 'max' => 7],
            [['semester'], 'string', 'max' => 1],
            [['academic_year'], 'string', 'max' => 9],
            [['location'], 'string', 'max' => 500],
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
            'status' => Yii::t('app', 'Status'),
            'location' => Yii::t('app', 'Location'),
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

    public function add($faculty_id)
    {
        if ($this->isNewRecord) {
            $result = true;
            foreach ($this->courses as $course) {
                $identity = \Yii::$app->user->identity;
                $this->user_id = $identity->id;
                $this->faculty_id = $faculty_id;
                $this->course_id = $course;
                $this->location = $this->_getPdfLocation();
                $this->status = self::STATUS_NEW;
                $this->created_at = new Expression('NOW()');

                $result = $result && $this->save();
                if ($result) {
                    $this->_generatePdfNotice();
                }
            }
            if ($result) {
                return true;
            }
        }
        return false;
    }

    private function _generatePdfNotice()
    {
        $template = Template::findOne($this->template_id);
        $course = $this->_findCourse($this->course_id);
        $faculty = $this->_findFaculty($this->faculty_id);
        //$model = self::findOne($this->id);
        if ($template !== null) {
            $pdf = $this->getPdf();
            $dateFormatter = \Yii::$app->formatter;
            $content = $template->content;

            $content = str_replace('{{reference_number}}', $this->reference_number, $content);
            $content = str_replace('{{course_code}}', $course->code, $content);
            $content = str_replace('{{course_title}}', $course->title, $content);
            //$content = str_replace('{{date_now}}', $dateFormatter->asDate($model->created_at, 'php:d F Y'), $content);

            $pdf->filename = $this->location;
            $pdf->content = $content;
            $pdf->render();
            return;
        }
    }

    public function getPdf()
    {
        $pdf = new Pdf([
            'marginLeft' => 25.4,
            'marginRight' => 25.4,
            'marginTop' => 12.7,
            'marginBottom' => 15.24,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_FILE,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.header {font-family: "Times New Roman", Georgia, Serif;}.title {font-size: 13pt;}.office {font-size: 8pt;}.title,.office {font-weight: bold; text-align: center;}.content{font-size: 10pt; text-align: justify;}.signature {padding-top: 28px;}',
        ]);
        $mpdf = $pdf->getApi();
        $mpdf->defaultfooterfontsize = 8;
        $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->SetFooter('3rd Floor, UPOU Main Building, Los Ba&ntilde;os, Laguna, Philippines - Telefax: (6349)5366010 or 5366001 to 06 ext 821, 333, 332 fmds@upou.edu.ph - www.upou.edu.ph');
        return $pdf;
    }

    private function _getPdfLocation()
    {
        
        return $this->_getPath() . '/' . $this->_getFilename();
    }

    private function _setPath()
    {
        $this->_path = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'notices';;
        if (!file_exists($this->_path)) {
            mkdir($this->_path, 0755, true);
        }
    }

    private function _getPath()
    {
        return $this->_path;
    }

    private function _setFilename()
    {
        $course = $this->_findCourse($this->course_id);
        $faculty = $this->_findFaculty($this->faculty_id);
        if (($course !== null) && ($faculty !== null)) {
            $this->_filename = 'Notice_' . $faculty->last_name . '-' .$course->code . '-' . time() . '.pdf';
        }
    }

    private function _getFilename()
    {
        return $this->_filename;
    }

    private function _findCourse($course_id)
    {
        $this->_course = Course::findOne($course_id);
        return $this->_course;
    }

    private function _findFaculty($faculty_id)
    {
        $this->_faculty = Faculty::findOne($faculty_id);
        return $this->_faculty;
    }
}
