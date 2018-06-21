<?php

namespace app\controllers;

/**
 * Class DefaultController
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\controllers
 */
class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
