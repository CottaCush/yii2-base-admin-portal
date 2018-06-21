<?php

namespace app\controllers;

use app\exceptions\DataLoadException;
use CottaCush\Yii2\Helpers\Html;
use CottaCush\Yii2\Controller\BaseController as UtilsBaseController;
use yii\base\Model;

/**
 * Class BaseController
 * @package app\controllers
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class BaseController extends UtilsBaseController
{

    /**
     * This flashes error message and sends to the view
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $message
     */
    public function flashError($message)
    {
        \Yii::$app->session->setFlash('error', $message);
    }

    /**
     * This flashes success message and sends to the view
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $message
     */
    public function flashSuccess($message)
    {
        \Yii::$app->session->setFlash('success', $message);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     * @return mixed
     */
    public function getUserId()
    {
        return $this->getModuleUser()->id;
    }

    /**
     * show flash messages
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param bool $sticky
     * @return string
     */
    public function showFlashMessages($sticky = false)
    {
        $timeout = $sticky ? 0 : 5000;
        $flashMessages = [];
        $allMessages = $this->getSession()->getAllFlashes();
        foreach ($allMessages as $key => $message) {
            if (is_array($message)) {
                $message = $this->mergeFlashMessages($message);
            }
            $flashMessages[] = [
                'message' => $message,
                'type' => $key,
                'timeout' => $timeout
            ];
        }
        $this->getSession()->removeAllFlashes();
        return Html::script('var notifications =' . json_encode($flashMessages));
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        //$this->loginRequireBeforeAction();
        return parent::beforeAction($action);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     * @param array $postData
     * @param string $className
     * @param null $sessionKey
     * @throws DataLoadException
     * @return Model
     */
    public function loadPostData(array $postData, $className, $sessionKey = null)
    {
        $this->isPostCheck($this->getRequest()->referrer);
        /** @var Model $form */
        $form = new $className();
        $form->load($postData);

        if (!$form->validate()) {
            if ($sessionKey) {
                $this->getSession()->set($sessionKey, $form->toArray());
            }
            $errors = $form->getErrorSummary(true);
            throw new DataLoadException($errors[0], 400);
        }
        return $form;
    }
}
