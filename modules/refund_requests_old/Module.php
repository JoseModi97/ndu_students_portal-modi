<?php

namespace app\modules\caution_refund;

use yii\base\Module as BaseModule;

/**
 * caution_refund module definition class
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
    public $controllerNamespace = 'app\modules\caution_refund\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
