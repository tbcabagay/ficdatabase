<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;
use yii\db\Expression;
use kartik\mpdf\Pdf;
use yii\helpers\Html;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $faculty_id
 * @property integer $template_id
 * @property string $semester
 * @property string $academic_year
 * @property string $date_course_start
 * @property string $date_final_exam
 * @property string $date_submission
 * @property string $reference_number
 *
 * @property Template $template
 * @property Faculty $faculty
 * @property User $user
 * @property Storage[] $storages
 */
class Notice extends \yii\db\ActiveRecord
{
    public $courses;
    public $storage_id;

    private $_faculty = null;
    private $_course = null;
    private $_path = null;
    private $_filename = null;
    private $_semesters = null;
    private $_identity = null;
    private $_storage;

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
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faculty_id', 'template_id', 'semester', 'academic_year', 'date_course_start', 'date_final_exam', 'date_submission', 'reference_number', 'courses'], 'required'],
            [['user_id', 'faculty_id', 'template_id'], 'integer'],
            [['reference_number'], 'unique'],
            [['date_course_start', 'date_final_exam', 'date_submission'], 'safe'],
            [['semester'], 'string', 'max' => 1],
            [['academic_year'], 'validateAcademicYear'],
            [['academic_year'], 'string', 'max' => 9],
            [['reference_number'], 'string', 'max' => 7],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['template_id' => 'id']],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faculty::className(), 'targetAttribute' => ['faculty_id' => 'id']],
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
            'faculty_id' => Yii::t('app', 'Faculty'),
            'template_id' => Yii::t('app', 'Template'),
            'semester' => Yii::t('app', 'Semester'),
            'academic_year' => Yii::t('app', 'Academic Year'),
            'date_course_start' => Yii::t('app', 'Date Course Start'),
            'date_final_exam' => Yii::t('app', 'Date Final Exam'),
            'date_submission' => Yii::t('app', 'Date Submission'),
            'reference_number' => Yii::t('app', 'Reference Number'),
        ];
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
    public function getFaculty()
    {
        return $this->hasOne(Faculty::className(), ['id' => 'faculty_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorages()
    {
        return $this->hasMany(Storage::className(), ['notice_id' => 'id']);
    }

    public function generateReferenceNumber()
    {
        $year = substr(date('Y'), -2);
        $model = self::find()
            ->select(['reference_number'])
            ->where(['like', 'reference_number', $year])
            ->orderBy('reference_number DESC')
            ->limit(1)
            ->one();
        if ($model !== null) {
            $explode = explode('-', $model->reference_number);
            $explode[1] +=  1;
            $explode[1] = str_pad($explode[1], 4, 0, STR_PAD_LEFT);
            $this->reference_number = implode('-', $explode);
        } else {
            $this->reference_number = $year . '-0001';
        }
    }

    public function add($faculty_id)
    {
        if (($this->_faculty = Faculty::findOne($faculty_id)) !== null) {
            if ($this->isNewRecord) {
                $this->_identity = Yii::$app->user->identity;
                $this->faculty_id = $this->_faculty->id;
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($this->save()) {
                        foreach ($this->courses as $course_id) {
                            $this->_course = Course::findOne($course_id);
                            if ($this->_course !== null) {
                                $this->_storage = new Storage([
                                    'notice_id' => $this->id,
                                    'course_id' => $this->_course->id,
                                    'status' => Storage::STATUS_NEW,
                                    'location' => $this->_getPath() . DIRECTORY_SEPARATOR . $this->_getFilename(),
                                    'created_at' => new Expression('NOW()'),
                                ]);
                                if ($this->_storage->save()) {
                                    $this->_createNotice();
                                    $this->_emailNotice();
                                }
                            }
                        }
                    }
                    $transaction->commit();
                    return true;
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
            return false;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function _createNotice()
    {
        $dateFormatter = \Yii::$app->formatter;
        $course = $this->_course;
        $faculty = $this->_faculty;
        $this->_storage->refresh();

        $content = $this->template->content;
        $content = str_replace('{{office_name}}', strtoupper($this->_identity->office->name), $content);
        $content = str_replace('{{designation_name_uppercase}}', strtoupper($faculty->designationName), $content);
        $content = str_replace('{{designation_name}}', $faculty->designationName, $content);
        $content = str_replace('{{reference_number}}', $this->reference_number, $content);
        $content = str_replace('{{semester}}', $this->getSemesterValue($this->semester), $content);
        $content = str_replace('{{academic_year}}', $this->academic_year, $content);
        $content = str_replace('{{course_code}}', $course->code, $content);
        $content = str_replace('{{course_title}}', $course->title, $content);
        $content = str_replace('{{date_now}}', $dateFormatter->asDate($this->_storage->created_at, 'php:d F Y'), $content);
        $content = str_replace('{{date_academic_start}}', $dateFormatter->asDate($this->date_course_start, 'php:d F Y'), $content);
        $content = str_replace('{{date_academic_final_exam}}', $dateFormatter->asDate($this->date_final_exam, 'php:d F Y'), $content);
        $content = str_replace('{{date_submission}}', $dateFormatter->asDate($this->date_submission, 'php:d F Y'), $content);
        $content = str_replace('{{name}}', strtoupper($faculty->name), $content);

        $pdf = $this->getPdf();
        $pdf->filename = $this->_storage->location;
        $pdf->content = $content;
        $pdf->render();
    }

    private function _emailNotice()
    {
        $faculty = $this->_faculty;
        $storage = $this->_storage;

        $absoluteConfirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm']);
        $body = '<p>Hello ' . $faculty->designationName . ',</p>';
        $body .= '<p>In order to complete your registration, please click the link below</p>';
        $body .= '<p>' . Html::a($absoluteConfirmLink, $absoluteConfirmLink) . '</p>';
        Yii::$app->mailer->compose()
            ->setTo($faculty->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('FIC Database Email Confirmation')
            ->setHtmlBody($body)
            ->attach($storage->location)
            ->send();
    }

    public function getPdf()
    {
        /*$pdf = Yii::$app->pdf;
        $pdf->destination = Pdf::DEST_FILE;
        $mpdf = $pdf->getApi();
        $mpdf->defaultfooterfontsize = 8;
        $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->SetFooter($this->_identity->office->footer_information);*/
        $pdf = new Pdf([
            'marginLeft' => 12.7,
            'marginRight' => 12.7,
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
        $mpdf->SetFooter($this->_identity->office->footer_information);
        return $pdf;
    }

    private function _getPath()
    {
        if ($this->_path === null) {
            $this->_setPath();
        }
        return $this->_path;
    }

    private function _setPath()
    {
        $path = \Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'notices';
        if (file_exists($path) === false) {
            mkdir($path, 0755, true);
        }
        $this->_path = $path;
    }

    private function _getFilename()
    {
        $this->_setFilename();
        return $this->_filename;
    }

    private function _setFilename()
    {
        $course = $this->_course;
        $faculty = $this->_faculty;
        $this->_filename = 'Notice_' . $faculty->name . '-' . $course->code . '-' . time() . '.pdf';
    }

    public function getSemesterRadioList()
    {
        if ($this->_semesters === null) {
            $this->_semesters = [
                1 => '1st',
                2 => '2nd',
            ];
        }
        return $this->_semesters;
    }

    public function getSemesterValue($id) {
        $semesters = $this->getSemesterRadioList();
        if (isset($semesters[$id])) {
            return $semesters[$id];
        }
        return;
    }

    public function validateAcademicYear($attribute, $params)
    {
        $pattern = '/^\d{4}-\d{4}$/';
        $isMatch = preg_match($pattern, $this->$attribute);
        if ($isMatch === 0) {
            $this->addError($attribute, Yii::t('app', 'Academic year must be in, ex. {academicYearFormat}, format.', ['academicYearFormat' => '(2015-2016)']));
        }
    }
}
