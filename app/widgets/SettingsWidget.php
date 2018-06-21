<?php

namespace app\widgets;

use app\constants\Constants;
use app\constants\SettingsConstants;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class SettingsWidget
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package app\widgets
 */
class SettingsWidget extends Widget
{
    public $periodicJobSetting;
    public $targetWordSetting;
    public $highValuePrizeSetting;
    public $mediumValuePrizeSetting;
    public $lowValuePrizeSetting;

    public function run()
    {
        echo Html::beginTag('table', ['class' => 'table table-responsive table-striped table-bordered']);
        $this->renderTableHeading();
        $this->renderHighValuePrizeSection();
        $this->renderMediumValuePrizeSection();
        $this->renderLowValuePrizeSection();
        $this->renderTargetWordSection();
        $this->renderPeriodicTimeSection();
        echo Html::endTag('table');
    }

    public function renderTableHeading()
    {
        echo Html::beginTag('tr', ['class' => '']);

        echo Html::tag('td', 'S/N', ['class' => '']);
        echo Html::tag('td', 'Name', ['class' => '']);
        echo Html::tag('td', 'Description', ['class' => '']);
        echo Html::tag('td', 'Value', ['class' => '']);
        echo Html::tag('td', 'Action', ['class' => '']);

        echo Html::endTag('tr');
    }

    public function renderHighValuePrizeSection()
    {
        echo Html::beginTag('tr', ['class' => '']);

        echo Html::tag('td', '1', ['class' => '']);
        echo Html::tag('td', 'High-Value Prizes', ['class' => '']);
        echo Html::tag('td',
            'This is the minimum amount and number of enrollment years that qualifies a customer' .
            ' for a high-valued prize.',
            ['class' => '']
        );
        $amount = $this->highValuePrizeSetting->amountModel->value;
        $years = $this->highValuePrizeSetting->enrollmentYearsModel->value;
        echo Html::tag(
            'td',
            Constants::NAIRA_SYMBOL . $amount . ', ' . $years . ' years',
            ['class' => '']
        );
        echo Html::tag('td',
            $this->renderActionButton('#highValuePrizeModal', ['amount' => $amount, 'years' => $years]),
            ['class' => '']
        );

        echo Html::endTag('tr');
    }

    public function renderMediumValuePrizeSection()
    {
        echo Html::beginTag('tr', ['class' => '']);

        echo Html::tag('td', '2', ['class' => '']);
        echo Html::tag('td', 'Medium-Value Prizes', ['class' => '']);
        echo Html::tag('td',
            'This is the minimum amount and number of enrollment years that qualifies a customer' .
            ' for a medium-valued prize.',
            ['class' => '']
        );
        $amount = $this->mediumValuePrizeSetting->amountModel->value;
        $years = $this->mediumValuePrizeSetting->enrollmentYearsModel->value;
        echo Html::tag(
            'td',
            Constants::NAIRA_SYMBOL . $amount . ', ' . $years . ' years',
            ['class' => '']
        );
        echo Html::tag(
            'td',
            $this->renderActionButton('#mediumValuePrizeModal', ['amount' => $amount, 'years' => $years]),
            ['class' => '']
        );

        echo Html::endTag('tr');
    }

    public function renderLowValuePrizeSection()
    {
        echo Html::beginTag('tr', ['class' => '']);

        echo Html::tag('td', '3', ['class' => '']);
        echo Html::tag('td', 'Low-Value Prizes', ['class' => '']);
        echo Html::tag('td',
            'This is the minimum amount and number of enrollment years that qualifies a customer' .
            ' for a low-valued prize.',
            ['class' => '']
        );
        $amount = $this->lowValuePrizeSetting->amountModel->value;
        $years = $this->lowValuePrizeSetting->enrollmentYearsModel->value;
        echo Html::tag(
            'td',
            Constants::NAIRA_SYMBOL . $amount . ', ' . $years . ' years',
            ['class' => '']
        );
        echo Html::tag(
            'td',
            $this->renderActionButton('#lowValuePrizeModal', ['amount' => $amount, 'years' => $years]),
            ['class' => '']
        );

        echo Html::endTag('tr');
    }

    public function renderTargetWordSection()
    {
        echo Html::beginTag('tr', ['class' => '']);

        echo Html::tag('td', '4', ['class' => '']);
        echo Html::tag('td', 'Minimum Length of Target Word', ['class' => '']);
        echo Html::tag('td',
            'This is minimum number of letters in the target word',
            ['class' => '']
        );
        echo Html::tag('td', $this->targetWordSetting->value, ['class' => '']);
        echo Html::tag(
            'td',
            $this->renderActionButton(
                '#targetWordModal',
                ['id' => $this->targetWordSetting->id, 'value' => $this->targetWordSetting->value]
            ),
            ['class' => '']
        );

        echo Html::endTag('tr');
    }

    public function renderPeriodicTimeSection()
    {
        echo Html::beginTag('tr', ['class' => '']);

        echo Html::tag('td', '5', ['class' => '']);
        echo Html::tag('td', 'Periodic Job Time', ['class' => '']);
        echo Html::tag('td',
            'This is the time to run the periodic job for sales records daily',
            ['class' => '']
        );
        echo Html::tag('td', SettingsConstants::HOURS_MAP[$this->periodicJobSetting->value], ['class' => '']);
        echo Html::tag(
            'td',
            $this->renderActionButton(
                '#timeModal',
                ['id' => $this->periodicJobSetting->id, 'value' => $this->periodicJobSetting->value]
            ),
            ['class' => '']
        );

        echo Html::endTag('tr');
    }

    public function renderActionButton($targetModal, array $data = [])
    {
        return Html::button(
            'Edit',
            [
                'class' => 'btn btn-sm',
                'data' => array_merge($data, [
                    'toggle' => 'modal',
                    'target' => $targetModal
                ])
            ]
        );
    }
}
