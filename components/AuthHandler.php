<?php
namespace app\components;

use app\models\Auth;
use app\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentification via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $session = Yii::$app->getSession();

        $attributes = $this->client->getUserAttributes();
        $domain = ArrayHelper::getValue($attributes, 'domain');
        $email = ArrayHelper::getValue(
            ArrayHelper::getValue(
                ArrayHelper::getValue($attributes, 'emails'), 0), 'value');
        $id = ArrayHelper::getValue($attributes, 'id');
        
        $user = User::find()->where([
            'email' => $email,
        ])->limit(1)->one();

        if (Yii::$app->user->isGuest) {
            if ($user !== null) {
                $auth = Auth::find()->where([
                    'source' => $this->client->getId(),
                    'source_id' => $id,
                ])->limit(1)->one();

                if ($auth === null) {
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $this->client->getId(),
                        'source_id' => (string)$id,
                    ]);
                    if ($auth->save() === false) {
                        $session->setFlash('error', [
                            Yii::t('app', 'Unable to save {client} account: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($auth->getErrors()),
                            ]),
                        ]);
                    }
                }
                Yii::$app->user->login($user, Yii::$app->params['rememberMeDuration']);
            } else {
                $session->setFlash('error', [
                    Yii::t('app', 'Email is not registered.'),
                ]);
            }
        }
    }
}