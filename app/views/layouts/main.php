<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use app\widgets\ContentHeaderWidget;
use CottaCush\Yii2\Assets\ToastrNotificationAsset;
use CottaCush\Yii2\Helpers\Html;
use yii\helpers\ArrayHelper;

ToastrNotificationAsset::register($this);
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) . ' &ndash; ' . Yii::$app->name; ?></title>
    <link rel="icon" type="image/png" href="/favicon.ico"/>
    <?php $this->head() ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition sidebar-mini fixed app-skin">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?= $this->render('../elements/header', ['user' => Yii::$app->user->getIdentity()]); ?>
    <?= $this->render('../elements/sidebar', ['user' => Yii::$app->user->getIdentity()]); ?>

    <div class="content-wrapper <?= ArrayHelper::getValue($this->params, 'content-wrapper-class'); ?>">
        <?= (ArrayHelper::getValue($this->params, 'show-content-header', true)) ?
            ContentHeaderWidget::widget(['icon' => ArrayHelper::getValue($this->params, 'content-header-icon', null)]) : '';
        ?>
        <section class="content">
            <?= $content ?>
        </section>
    </div>
    <?= $this->render('../elements/footer'); ?>
</div>

<?= $this->context->showFlashMessages(); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
