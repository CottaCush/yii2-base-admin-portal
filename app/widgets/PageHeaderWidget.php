<?php

namespace app\widgets;

use CottaCush\Yii2\Helpers\Html;
use CottaCush\Yii2\Widgets\BaseWidget;

/**
 * Class PageHeaderWidget
 * @package app\widgets
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 */
class PageHeaderWidget extends BaseWidget
{
    public $title;
    public $icon;
    public $buttonText;
    public $modalTargetId;
    public $url;

    public function init()
    {
        parent::init();
        $this->view->title = $this->title;

        if ($this->buttonText) {
            $this->view->params['content-header-button'] =
                Html::a(
                    Html::baseIcon('fa fa-plus') . $this->buttonText,
                    $this->url,
                    [
                        'class' => 'btn btn-sm content-header-btn btn-primary',
                        'data' => $this->url ? [] : [
                            'toggle' => 'modal', 'target' => '#' . $this->modalTargetId
                        ]
                    ]
                );
        }

        $this->view->params['content-header-icon'] = $this->icon;
    }
}
