<?php

namespace app\modules\refund_requests;

use yii\base\Module as BaseModule;

/**
 * refund_requests module definition class
 */
class Module extends BaseModule
{
    /**
     * @var bool Whether to override/bypass the fee balance requirement check.
     */
    public $overrideFeeBalance = true;

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
