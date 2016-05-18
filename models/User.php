<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $email
 * @property integer $status
 * @property integer $office_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth[] $auths
 * @property Notice[] $notices
 * @property Template[] $templates
 * @property Office $office
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 20;

    private static $_status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_key', 'email', 'status', 'office_id', 'created_at'], 'required'],
            [['status', 'office_id'], 'integer'],
            [['email'], 'email'],
            [['email'], 'validateEmailDomain'],
            [['created_at', 'updated_at'], 'safe'],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 255],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::className(), 'targetAttribute' => ['office_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'office_id' => Yii::t('app', 'Office ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotices()
    {
        return $this->hasMany(Notice::className(), ['user_id' => 'id']);
    }
 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates() 
    { 
        return $this->hasMany(Template::className(), ['user_id' => 'id']); 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }

    public function validateEmailDomain($attribute, $params)
    {
        list($user, $domain) = split('@', $this->$attribute);
        if ($domain !== Yii::$app->params['allowedDomain']) {
            $this->addError($attribute, Yii::t('app', 'The email must be {allowedDomain} only.', ['allowedDomain' => Yii::$app->params['allowedDomain']]));
        }
    }

    public function add()
    {
        if ($this->isNewRecord) {
            $this->auth_key = \Yii::$app->security->generateRandomString();
            $this->status = self::STATUS_ACTIVE;
            $this->created_at = new Expression('NOW()');

            if ($this->save()) {
                return true;
            }
        }
        return false;
    }

    public static function getListStatus()
    {
        self::$_status = [];
        return self::$_status = [
            self::STATUS_ACTIVE => 'ACTIVE',
            self::STATUS_DELETE => 'DELETED',
        ];
    }
}
