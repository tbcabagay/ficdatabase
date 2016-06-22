<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $email
 * @property string $password
 * @property integer $role
 * @property integer $status
 * @property integer $faculty_id
 * @property integer $office_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth[] $auths
 * @property Notice[] $notices
 * @property Template[] $templates
 * @property Office $office
 * @property Faculty $faculty
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_NEW = 10;
    const STATUS_ACTIVE = 20;
    const STATUS_DELETE = 30;
    const SCENARIO_SITE_CREATE = 'site_create';
    const SCENARIO_USER_CREATE = 'user_create';
    const ROLE_FACULTY = 10;
    const ROLE_USER = 20;

    private static $_status;

    public $confirm_password;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SITE_CREATE] = ['email', 'password', 'confirm_password'];
        $scenarios[self::SCENARIO_USER_CREATE] = ['email', 'office_id'];
        return $scenarios;
    }

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
    public function behaviors()
    {
        return [
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
            [['auth_key', 'email', /*'office_id',*/ 'role', 'status'], 'required'],
            [['password'], 'required', 'on' => self::SCENARIO_SITE_CREATE],
            [['role', 'status', 'faculty_id', 'office_id'], 'integer'],
            [['email'], 'email'],
            [['email'], 'unique'],
            /*[['email'], 'validateEmailDomain'],*/
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => 'Passwords don\'t match', 'on' => self::SCENARIO_SITE_CREATE],
            [['created_at', 'updated_at'], 'safe'],
            [['auth_key'], 'string', 'max' => 32],
            [['email', 'password'], 'string', 'max' => 255],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faculty::className(), 'targetAttribute' => ['faculty_id' => 'id']],
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
            'password' => Yii::t('app', 'Password'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'faculty_id' => Yii::t('app', 'Faculty ID'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaculty()
    {
        return $this->hasOne(Faculty::className(), ['id' => 'faculty_id']);
    }

    public function validateEmailDomain($attribute, $params)
    {
        list($user, $domain) = split('@', $this->$attribute);
        if ($domain !== Yii::$app->params['allowedDomain']) {
            $this->addError($attribute, Yii::t('app', 'The email must be {allowedDomain} only.', ['allowedDomain' => Yii::$app->params['allowedDomain']]));
        }
    }

    public function siteCreate()
    {
        if ($this->isNewRecord) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $this->auth_key = \Yii::$app->security->generateRandomString();

            if ($this->save(false)) {
                return true;
            }
        }
        return false;
    }

    public function userCreate()
    {
        if ($this->isNewRecord) {
            $this->role = self::ROLE_USER;
            $this->status = self::STATUS_ACTIVE;

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

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function confirmEmail()
    {
        $absoluteConfirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm', 'id' => $this->id, 'code' => $this->auth_key]);
        $body = '<p>Hello ' . $this->faculty->designationName . ',</p>';
        $body .= '<p>In order to complete your registration, please click the link below</p>';
        $body .= '<p>' . Html::a($absoluteConfirmLink, $absoluteConfirmLink) . '</p>';
        Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('FIC Database Email Confirmation')
            ->setHtmlBody($body)
            ->send();
    }
}
