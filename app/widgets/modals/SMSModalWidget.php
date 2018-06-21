<?php

namespace app\widgets\modals;

use app\constants\Messages;
use yii\helpers\Html;
use app\assets\SMSFormAsset;

/**
 * Class SmsModalWidget
 * @package app\widgets\modals
 * @author Bolade Oye <bolade@cottacush.com>
 */
class SMSModalWidget extends BaseModalWidget
{
    public $campaigns;
    public $target;
    private $dropDownPrompt = 'Please choose one';
    public $smsFieldID;
    public $smsCharCounterID;

    public function init()
    {
        parent::init();
        SMSFormAsset::register($this->view);
        $this->title = ($this->populateFields ? 'Edit ' : 'Add ') . Messages::ENTITY_SMS;
    }

    public function renderContents()
    {
        parent::renderContents();
        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::beginTag('div', ['class' => 'col-sm-6']);
        echo $this->form->field($this->model, 'percentage_target')
            ->dropDownList($this->target, ['prompt' => $this->dropDownPrompt])
            ->label(
                'Target Customers ' . Html::tag(
                    'span',
                    '',
                    [
                        'class' => 'fa fa-question-circle',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'right',
                        'title' => 'Customers with a % of the target word'
                    ]
                )
            );
        echo Html::endTag('div');//col-sm-6

        echo Html::endTag('div');//Row

        echo $this->form->field($this->model, 'message')->textarea([
            'id' => $this->smsFieldID,
            'data-sms-field' => true,
            'rows' => '4', 'maxLength' => '160'])
            ->hint('Character Count: ' .
                Html::tag('span', '0', [
                    'id' => $this->smsCharCounterID,
                    'data-sms-counter' => true
                ]) . '/' .
                Html::tag('span', '160'));
    }
}
