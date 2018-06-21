<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\forms\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$this->params['card-footer'] = Html::a('Forgot Password ?', '/forgot-password');

$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form'],
    'action' => 'sign-in'
]);

echo $form->field($model, 'username');

echo $form->field($model, 'password')->passwordInput();

echo $form->field($model, 'rememberMe')->checkbox();

echo Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']);
ActiveForm::end();
