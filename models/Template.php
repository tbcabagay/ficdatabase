<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

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
 */
class Template extends \yii\db\ActiveRecord
{
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

    public function add()
    {
        if ($this->isNewRecord) {
            $this->user_id = 1;
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
            //var_dump($mpdf->defaultfooterfontsize);
            //exit();

            $pdf->content = $model->content;
            return $pdf->render();
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
