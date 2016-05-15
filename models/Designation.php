<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%designation}}".
 *
 * @property integer $id
 * @property string $abbreviation
 * @property string $title
 *
 * @property Faculty[] $faculties
 */
class Designation extends \yii\db\ActiveRecord
{
    private static $_listDesignation;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%designation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['abbreviation', 'title'], 'required'],
            [['abbreviation'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'abbreviation' => Yii::t('app', 'Abbreviation'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaculties()
    {
        return $this->hasMany(Faculty::className(), ['designation_id' => 'id']);
    }

    public static function getListDesignation()
    {
        self::$_listDesignation = ArrayHelper::map(self::find()->all(), 'id', 'title');
        return self::$_listDesignation;
    }
}
