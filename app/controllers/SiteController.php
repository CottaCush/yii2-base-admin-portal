<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\services\DummyServiceInterface;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class SiteController extends BaseController
{
    const LOGIN_DATA_KEY = '_login_form';
    const DEFAULT_URL = 'default/index';

    /** @var  DummyServiceInterface */
    protected DummyServiceInterface $dummyService;

    public function __construct($id, $module, DummyServiceInterface $dummyService, $config = [])
    {
        $this->dummyService = $dummyService;
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex(): Response
    {
        return $this->redirect(self::DEFAULT_URL);
    }

    public function actionServiceTest()
    {
        return $this->dummyService->shout("Hello World");
    }

    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = $this->getSession()->get(self::LOGIN_DATA_KEY, new LoginForm());
        $this->layout = 'card';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact(): Response|string
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

    /**
     * @return Response|string
     * @throws ForbiddenHttpException
     */
    public function actionAbout(): Response|string
    {
        if ($this->getUser()->isGuest) {
            return $this->getUser()->loginRequired();
        }
        return $this->render('about');
    }
}
