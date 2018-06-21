<?php

namespace app\widgets;

use app\assets\RedeemPrizeFormAsset;
use app\libs\Utils;
use app\models\Winnings;
use CottaCush\Yii2\Date\DateFormat;
use CottaCush\Yii2\Date\DateUtils;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class RedeemPrizeWidget
 * @author Bolade Oye <boade@cottacush.com>
 * @package app\widgets
 */
class RedeemPrizeWidget extends Widget
{
    /** @var  Winnings */
    public $winning;

    public function run()
    {
        RedeemPrizeFormAsset::register($this->view);

        echo Html::beginTag('div', ['id' => 'redeem_prize_box']);
        echo Html::beginTag('div', ['class' => 'redeem-prize clearfix', 'id' => 'xfav']);
        echo Html::tag('h4', 'Customer Information', ['class' => 'redeem-prize__heading']);
        echo Html::tag('h2', $this->winning->customer->getFullName(), ['class' => 'redeem-prize__name']);
        echo Html::tag('p', '(' . $this->winning->customer->gender . ')', ['class' => 'redeem-prize__gender']);

        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo Html::tag('span', 'Email:', ['class' => 'redeem-prize__label']);
        echo Html::tag('span', $this->winning->customer->email, ['class' => 'redeem-prize__info']);
        echo Html::endTag('div'); //col-md-4

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo Html::tag('span', 'Phone Number:', ['class' => 'redeem-prize__label']);
        echo Html::tag('span', $this->winning->customer->phone, ['class' => 'redeem-prize__info']);
        echo Html::endTag('div'); //col-md-4

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo Html::tag('span', 'Winnings:', ['class' => 'redeem-prize__label']);
        $prize = ($this->winning->prize) ? $this->winning->prize->name : '';
        echo Html::tag('span', $prize, ['class' => 'redeem-prize__info']);
        echo Html::endTag('div'); //col-md-4

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo Html::tag('span', 'Campaign:', ['class' => 'redeem-prize__label']);
        echo Html::tag('span', $this->winning->campaign->name, ['class' => 'redeem-prize__info']);
        echo Html::endTag('div'); //col-md-4

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo Html::tag('span', 'Status:', ['class' => 'redeem-prize__label']);
        echo Html::tag('span', Utils::getStatusHtml($this->winning->status), ['class' => 'redeem-prize__info']);
        echo Html::endTag('div'); //col-md-4

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo Html::tag('span', 'Achieved at:', ['class' => 'redeem-prize__label']);
        echo Html::tag(
            'span',
            DateUtils::format($this->winning->achieved_at, DateFormat::FORMAT_DATE_TIME_12H),
            ['class' => 'redeem-prize__info']
        );
        echo Html::endTag('div'); //col-md-4

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo Html::tag('span', 'Redeemed at:', ['class' => 'redeem-prize__label']);
        $redeemedAt = ($this->winning->redeemed_at) ?
            DateUtils::format($this->winning->redeemed_at, DateFormat::FORMAT_DATE_TIME_12H) :
            'Not yet redeemed';
        echo Html::tag('span', $redeemedAt, ['class' => 'redeem-prize__info']);
        echo Html::endTag('div'); //col-md-4

        if ($this->winning->isPending()) {
            echo Html::beginTag('div', ['class' => 'col-md-12']);
            $winningId = $this->winning->id;
            echo Html::a(
                'Redeem Prize  <i class="glyphicon glyphicon-print"></i>',
                ["generate-receipt?id=$winningId"],
                [
                    'class'=>'btn-pdfprint btn btn-primary redeem-prize__button',
                    'data-pjax'=>'0'
                ]
            );
            echo Html::endTag('div'); //col-md-12
        }
        echo Html::endTag('div'); //row

        echo Html::endTag('div');
        echo Html::endTag('div');
    }

}
