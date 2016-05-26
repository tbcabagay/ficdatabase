<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%template}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Notice[] $notices
 * @property User $user
 * @property Notice[] $notices
 */
class Template extends \yii\db\ActiveRecord
{
    private static $_listTemplate;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template}}';
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
            [['name', 'content'], 'required'],
            [['user_id'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
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
            'name' => Yii::t('app', 'Name'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotices()
    {
        return $this->hasMany(Notice::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function download($id)
    {
        $model = self::find()->select(['content'])->where(['id' => $id])->limit(1)->one();
        if ($model !== null) {
            $identity = \Yii::$app->user->identity;
            $pdf = Yii::$app->pdf;
            $mpdf = $pdf->getApi();
            $mpdf->defaultfooterfontsize = 8;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->SetFooter($identity->office->footer_information);

            $pdf->content = $model->content;
            return $pdf->render();
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function getListTemplate()
    {
        $identity = \Yii::$app->user->identity;
        $user = User::findOne($identity->id);
        self::$_listTemplate = ArrayHelper::map($user->getTemplates()->all(), 'id', 'name');
        return self::$_listTemplate;
    }
}
