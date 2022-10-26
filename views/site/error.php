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

$this->title = 'Error | ' . $name;
$exception = Yii::$app->errorHandler->exception;
?>

<h5>Error !</h5>

<p>
    <?= $exception->getMessage() ?>
    <br/>
    <br/>
    <br/>
    <br/>
    Do you need help? Send a message to smis_support@ndu.ac.ke
</p>