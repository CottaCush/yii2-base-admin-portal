<?php

namespace app\widgets;

use app\assets\CampaignFormAsset;
use app\constants\Constants;
use app\models\Campaign;
use app\models\forms\CampaignForm;
use app\models\Prize;
use app\models\Store;
use CottaCush\Yii2\Helpers\Html;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Class CampaignFormWidget
 * @author Bolade Oye <boade@cottacush.com>
 * @package app\widgets
 */
class CampaignFormWidget extends Widget
{
    /** @var  CampaignForm */
    public $model;
    public $campaignTypes;
    public $allocationMethods;
    public $allocations;

    /** @var  ActiveForm */
    private $form;
    private $prizes;
    private $stores;
    private $isEditRestricted = false;

    public function run()
    {
        CampaignFormAsset::register($this->view);
        $this->form = ActiveForm::begin(['id' => 'createCampaignForm', 'action' => Url::toRoute('save-campaign')]);
        $this->prizes = Prize::getActiveDropdownMap('id', 'name');
        $this->stores = Store::getActiveDropdownMap('id', 'location_name');
        $this->allocations = $this->model->allocation;
        $this->model->allocation = [];

        if ($this->model->id) {
            $campaign = Campaign::findOne($this->model->id);
            $this->isEditRestricted = ($campaign->isRunning() || $campaign->isEnded());
        }

        $this->renderStepOne();
        $this->renderStepTwo();
        $this->renderTemplateBlock();
        $this->form->end();
        $this->initializePrizes();
    }

    private function renderPrizeAllocationItem($prize = null, $store = null, $quantity = null)
    {
        echo Html::beginTag('div', ['class' => 'prize-allocation-item', 'data-prize-allocation-item' => '']);

        echo Html::beginTag('div', ['class' => 'prize-allocation-item__inner']);
        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo $this->form->field($this->model, 'allocation[prize][]')
                ->dropDownList($this->prizes, ['prompt' => 'Please choose a prize', 'value' => $prize, 'data-prize' => ''])
                ->label('Prize');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo $this->form->field($this->model, 'allocation[store][]')
            ->dropDownList($this->stores, ['prompt' => 'Please choose a store', 'value' => $store, 'data-store' => ''])
            ->label('Location Name');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo $this->form->field($this->model, 'allocation[quantity][]')
            ->textInput(['type' => 'number', 'min' => 0, 'value' => $quantity, 'data-quantity' => '', 'data-integer-input' => true])
            ->label('Quantity');
        echo Html::endTag('div');

        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::tag('div', '',
            ['class' => 'fa fa-close prize-allocation-closer', 'data-prize-allocation-closer' => '']
        );
        echo Html::endTag('div');
    }

    private function renderPrizeAllocations()
    {
        $allocations = $this->allocations;
        if ($allocations) {
            $prizes = ArrayHelper::getValue($allocations, 'prize');
            $stores = ArrayHelper::getValue($allocations, 'store');
            $quantity = ArrayHelper::getValue($allocations, 'quantity');
            $count = count($prizes);
            for ($i = 0; $i < $count; $i++) {
                $this->renderPrizeAllocationItem($prizes[$i], $stores[$i], $quantity[$i]);
            }
            $this->initializeAllocations();
        } else {
            $this->renderPrizeAllocationItem();
        }
    }



    private function renderStepOne()
    {
        echo Html::beginTag('div', ['class' => 'campaign-form', 'id' => 'step1Form']);
        $this->renderPageHeader('1', 'Campaign Information');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'campaign-form__body']);

        echo Html::beginTag('div', ['class' => 'row']);
        echo $this->form->field($this->model, 'id')->hiddenInput()->label(false);
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo $this->form->field($this->model, 'name')->textInput(['readonly' => $this->isEditRestricted])
            ->label('Campaign Name');
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo $this->form->field($this->model, 'type')->dropDownList($this->campaignTypes);
        echo Html::endTag('div');
        echo Html::endTag('div');

        $this->renderCampaignDateSection();
        $this->renderQualifyingAmountsSection();

        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo $this->form->field($this->model, 'target_word')->textInput(['readonly' => $this->isEditRestricted])
                ->hint('Customers will have to collect all the characters of the Target Word to win prizes.',
                    ['class' => 'form-text text-muted']
                );
        echo Html::endTag('div');
        echo Html::endTag('div');

        $this->renderCampaignSMSSection();

        echo Html::endTag('div'); //end-campaign-form-body

        echo Html::beginTag('div', ['class' => 'campaign-form__footer']);
        echo Html::button('Proceed', ['class' => 'btn btn-md btn-primary', 'id' => 'step1FwdBtn']);
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    private function renderStepTwo()
    {
        echo Html::beginTag('div', ['class' => 'campaign-form hide', 'id' => 'step2Form']);
        $this->renderPageHeader('2', 'Prize Allocation');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'campaign-form__body']);

        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-md-4']);
        echo $this->form->field($this->model, 'prize_allocation_method')
                ->radioList($this->allocationMethods, ['class' => 'radio allocation-radio'])
                ->label('Prize Allocation Method ' . Html::tag('span', '',
                    ['class' => 'fa fa-question-circle', 'data-toggle' => 'tooltip', 'data-placement' => 'right',
                        'title' => 'Automatic picks prizes for campaign winners, 
                             Manual allows Admin to physically pick preferred prizes to campaign winner'
                    ]
                )
            );
        echo Html::endTag('div');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['data-prize-allocation-list' => '']);
        $this->renderPrizeAllocations();
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo Html::a(Html::tag('span', '', ['class' => 'fa fa-plus']) .
                    "Add", '#', ['class' => 'btn btn-md btn-default', 'id' => 'addAllocation']
        );
        echo Html::endTag('div');
        echo Html::endTag('div');

        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'campaign-form__footer']);

        echo Html::button('Back', ['class' => 'btn btn-md ', 'id' => 'step2BackBtn']);
        echo Html::submitButton('Save', ['class' => 'btn btn-md btn-primary']);
        echo Html::endTag('div');

        echo Html::endTag('div');
    }

    private function renderPageHeader($pageNumber, $title)
    {
        echo Html::beginTag('div', ['class' => 'campaign-form__header']);
        echo Html::beginTag('div', ['class' => 'campaign-form__header-number']);
        echo Html::tag('h1', $pageNumber);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'campaign-form__header-desc']);
        echo Html::tag('h2', $title);
        echo Html::endTag('div');
    }

    private function renderCampaignDateSection()
    {
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo $this->form->field($this->model, 'start_date')
            ->textInput(['class' => 'form-control datepick', 'readonly' => $this->isEditRestricted]);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo $this->form->field($this->model, 'end_date')
            ->textInput(['class' => 'form-control datepick']);
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    private function renderQualifyingAmountsSection()
    {
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo $this->form->field($this->model, 'min_amount')
            ->textInput(['type' => 'number', 'step' => 1000, 'min' => 0])
            ->label('Minimum Amount spent to qualify ('. Constants::NAIRA_SYMBOL .') optional');
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        echo $this->form->field($this->model, 'max_amount')
            ->textInput(['type' => 'number', 'step' => 1000, 'min' => 0])
            ->label('Maximum Amount spent ('. Constants::NAIRA_SYMBOL .') optional');
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    private function renderCampaignSMSSection()
    {
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-md-6']);
        $campaignSMSHint = 'Please use '. Constants::LETTER_MARKER .' to indicate the Letter found and ' .
            Constants::TARGET_WORD_MARKER . ' to indicate the Target Word';
        echo $this->form->field($this->model, 'campaign_sms')
            ->textarea(['maxlength' => 160, 'rows' => 4,
                'placeholder' => $campaignSMSHint])
            ->hint('Character Count: ' .
                Html::tag('span', '0', ['id' => 'charUsedOpening']) . '/' .
                Html::tag('span', '160'))
            ->label('Campaign SMS ' . Html::tag('span', '',
                    ['class' => 'fa fa-question-circle', 'data-toggle' => 'tooltip',
                        'data-placement' => 'right', 'title' => $campaignSMSHint
                    ]
                )
            );
        echo Html::endTag('div'); // end-col-6

        echo Html::beginTag('div', ['class' => 'col-md-6']);
        $winningSMSHint = 'Please use ' . Constants::TARGET_WORD_MARKER . ' to indicate the Target Word and ' .
            Constants::STORE_MARKER . ' to indicate the Pickup Store Address';
        echo $this->form->field($this->model, 'winning_sms')
            ->textarea(['maxlength' => 160, 'rows' => 4,
                'placeholder' => $winningSMSHint])
            ->hint('Character Count: ' .
                Html::tag('span', '0', ['id' => 'charUsedClosing']) . '/' .
                Html::tag('span', '160'))
            ->label('Winning SMS ' . Html::tag('span', '',
                    ['class' => 'fa fa-question-circle', 'data-toggle' => 'tooltip',
                        'data-placement' => 'right', 'title' => $winningSMSHint
                    ]
                )
            );
        echo Html::endTag('div'); //end-col-6
        echo Html::endTag('div'); //end-row
    }

    private function renderTemplateBlock()
    {
        echo Html::beginTag('template', ['data-prize-allocation-template' => '']);
        $this->renderPrizeAllocationItem();
        echo Html::endTag('template');
    }

    private function initializePrizes()
    {
        $prizes = Prize::getRemainingQuantityMap();

        $this->view->registerJs(
            "App.Widgets.PrizeAllocationForm.initPrizes(". json_encode($prizes) . ");"
        );
    }

    private function initializeAllocations()
    {
        $this->view->registerJs(
            "App.Widgets.PrizeAllocationForm.initFormAllocations('" .
                $this->model->prize_allocation_method . "', " .
                json_encode($this->allocations) .
            ");"
        );
    }
}
