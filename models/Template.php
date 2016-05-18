<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

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
    public function rules()
    {
        return [
            [['user_id', 'name', 'created_at'], 'required'],
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotices() 
    { 
        return $this->hasMany(Notice::className(), ['template_id' => 'id']); 
    }

    public function add()
    {
        if ($this->isNewRecord) {
            $identity = \Yii::$app->user->identity;
            $this->user_id = $identity->id;
            $this->created_at = new Expression('NOW()');

            if ($this->save()) {
                return true;
            }
        }
        return false;
    }

    public function edit()
    {
        $this->updated_at = new Expression('NOW()');
        return $this->save();
    }

    public static function download($id)
    {
        $model = self::find()->select(['content'])->where(['id' => $id])->limit(1)->one();
        if ($model !== null) {
            $pdf = Yii::$app->pdf;
            $mpdf = $pdf->getApi();
            $mpdf->defaultfooterfontsize = 8;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->SetFooter('3rd Floor, UPOU Main Building, Los Ba&ntilde;os, Laguna, Philippines - Telefax: (6349)5366010 or 5366001 to 06 ext 821, 333, 332 fmds@upou.edu.ph - www.upou.edu.ph');

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
