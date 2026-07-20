<?php

namespace app\modules\refund_requests;

use yii\base\Module as BaseModule;

/**
 * refund_requests module definition class
 */
class Module extends BaseModule
{
    /**
     * @var bool Whether to bypass clearance, academic-status, and fee-balance
     * requirements. A refundable CAUTION MONEY record is always required.
     */
    public $overrideEligibility = false;

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\refund_requests\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

}
