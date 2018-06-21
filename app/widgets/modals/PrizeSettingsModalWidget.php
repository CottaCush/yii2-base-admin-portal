<?php

namespace app\widgets\modals;

use app\constants\Constants;
use app\models\forms\PrizeSettingForm;
use yii\helpers\Html;

/**
 * Class PrizeSettingsModalWidget
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package app\widgets\modals
 */
class PrizeSettingsModalWidget extends BaseModalWidget
{
    public function init()
    {
        parent::init();
        $this->title = 'Edit Setting';
    }

    public function renderContents()
    {
        parent::renderContents();

        /** @var PrizeSettingForm $model */
        $model = $this->model;
        echo $this->form->field($this->model, 'amount')
            ->label($model->amountModel->name . ' (' . Constants::NAIRA_SYMBOL . ') ' . Html::tag('span', '', [
                'class' => 'fa fa-question-circle',
                'data-toggle' => 'tooltip',
                'data-placement' => 'right',
                'title' => $model->amountModel->description
            ])
        );

        echo $this->form->field($this->model, 'years')
            ->label($model->enrollmentYearsModel->name . ' ' . Html::tag('span', '', [
                    'class' => 'fa fa-question-circle',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'right',
                    'title' => $model->enrollmentYearsModel->description
                ])
            );

        echo $this->form->field($this->model, 'type')->hiddenInput()->label(false);
    }
}
