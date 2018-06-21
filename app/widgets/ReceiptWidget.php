<?php

namespace app\widgets;

use app\models\Receipt;
use CottaCush\Yii2\Date\DateFormat;
use CottaCush\Yii2\Date\DateUtils;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class ReceiptWidget
 * @author Bolade Oye <boade@cottacush.com>
 * @package app\widgets
 */
class ReceiptWidget extends Widget
{
    const RECEIPT_NUMBER_LENGTH = 10;

    /** @var  Receipt */
    public $receipt;

    public function run()
    {
        echo Html::beginTag('div', ['class' => 'receipt']);
        $this->renderHeader();
        $this->renderBody();
        $this->renderWinnings();
        echo Html::endTag('div');
    }

    private function renderHeader()
    {
        echo Html::img('/images/logos/full-logo.png', ['alt' => 'Spar Logo', 'class' => 'receipt__logo']);
        echo Html::tag('h3', 'RECEIPT', ['class' => 'pull-right']);

        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::beginTag('div', ['class' => 'receipt__address col-xs-8']);
        echo Html::tag('div', $this->receipt->winning->store->location_name, ['class' => 'receipt__address-store-name']);
        echo Html::tag('div', $this->receipt->winning->store->location_code, ['class' => 'receipt__address-location']);
        echo Html::tag('div', $this->receipt->winning->store->state, ['class' => 'receipt__address-state']);
        echo Html::tag('div', '', ['class' => 'receipt__address-email']);
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'receipt__desc col-xs-4']);
        echo Html::tag('span', 'No: ' . str_pad($this->receipt->id, self::RECEIPT_NUMBER_LENGTH, "0", STR_PAD_LEFT));
        echo Html::endTag('div');
        echo Html::endTag('div');

        echo Html::beginTag('hr');
    }

    private function renderWinnings()
    {
        echo Html::beginTag('div', ['class' => 'receipt__winnings row']);
        echo Html::tag('div', '1', ['class' => 'receipt__winnings-quantity col-xs-1']);
        echo Html::tag('div', '&#x00D7;', ['class' => 'receipt__winnings-times col-xs-1']);
        $prize = ($this->receipt->winning->prize) ? $this->receipt->winning->prize->name : '';
        echo Html::tag('div', $prize, ['class' => 'receipt__winnings-winning col-xs-10']);
        echo Html::endTag('div');
    }

    private function renderBody()
    {
        echo Html::beginTag('div', ['class' => 'receipt__details row']);
        $this->renderFields('Date:', DateUtils::format($this->receipt->created_at, DateFormat::FORMAT_DATE_TIME_12H));
        $this->renderFields('Issued To:', $this->receipt->winning->customer->getFullName());
        $this->renderFields('Issued By:', $this->receipt->createdBy->getFullName());
        $this->renderFields('Campaign:', $this->receipt->winning->campaign->name);
        $this->renderFields('Otp:', $this->receipt->winning->otp);
        echo Html::endTag('div');
    }

    private function renderFields($label, $desc)
    {
        echo Html::tag('span', $label, ['class' => 'receipt__details-label col-xs-3']);
        echo Html::tag('span', $desc, ['class' => 'receipt__details-info col-xs-9']);
    }

}
