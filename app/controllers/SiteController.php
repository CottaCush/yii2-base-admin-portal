<?php

namespace app\controllers;

use app\constants\ErrorMessages;
use app\constants\Messages;
use app\exceptions\SparUserAuthenticationException;
use app\exceptions\ValidateInviteTokenException;
use app\models\ContactForm;
use app\models\forms\ForgotPasswordForm;
use app\models\forms\LoginForm;
use app\models\forms\ResetPasswordForm;
use app\models\forms\SignUpForm;
use app\models\Invite;
use app\models\SparUser;
use app\models\UserCredential;
use app\services\DummyServiceInterface;
use cottacush\userauth\exceptions\PasswordChangeException;
use cottacush\userauth\exceptions\UserAuthenticationException;
use cottacush\userauth\exceptions\UserCreationException;
use Yii;

class SiteController extends BaseController
{
    const LOGIN_DATA_KEY = '_login_form';
    const DEFAULT_URL = 'default/index';

    /** @var  DummyServiceInterface */
    protected $dummyService;

    public function __construct($id, $module, DummyServiceInterface $dummyService, $config = [])
    {
        $this->dummyService = $dummyService;
        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(self::DEFAULT_URL);
    }

    public function actionServiceTest()
    {
        return $this->dummyService->shout("Hello World");
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = $this->getSession()->get(self::LOGIN_DATA_KEY, new LoginForm());
        $this->layout = 'card';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        if ($this->getUser()->isGuest) {
            return $this->getUser()->loginRequired();
        }
        return $this->render('about');
    }
}
