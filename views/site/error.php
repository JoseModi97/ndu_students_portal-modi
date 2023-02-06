<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @desc This file displays the error page with messages
 */

/**
 * @var $this yii\web\View
 * @var string $name
 * @var string $title
 */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Error | ' . $name;
$exception = Yii::$app->errorHandler->exception;
?>

<h5>Error !</h5>

<p>
    <?= $exception->getMessage() ?>
    <br/>
    <br/>
    Do you need help? Send a message to smis_support@ndu.ac.ke
    <br/>
    <br/>
    <a href="<?= Url::to(['/site/login']) ?>">Click to go back home</a> OR
    <a href="<?= Url::to(Yii::$app->request->referrer) ?>">return to previous page</a>
</p>
