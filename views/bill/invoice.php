<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/27/2024
 * @time: 6:54 PM
 */

/**
 * @var string $name
 * @var string $regNumber
 * @var array $feeItems
 * @var string $balance
 * @var array $payableFees
 * @var string $invoiceFor
 */

use yii\helpers\Json;
use yii\helpers\Url;

?>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="page-header">
        </div>
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Please read instructions below:
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <ul>
                                <li>All administrative and tuition charges are mandatory</li>
                                <li>
                                    During course registration, if your balance is insufficient, you may register for a
                                    few courses.
                                    Once you have topped up your balance, you can then register for the remaining
                                </li>
                            </ul>
                            <div class="text-center text-info" style="font-size: large;">
                                Your balance is <?= Yii::$app->formatter->asCurrency($balance); ?>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                You will be charged for the following items:
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="prog-charges">
                                <div class="loader"></div>
                                <div class="error-display alert text-center" role="alert"></div>
                            </div>
                            <div class="pull-right" style="margin-bottom: 10px;">
                                <button id="pay" class="btn btn-success">Make Payment</button>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 10px; font-weight: bold;">#</th>
                                    <th style="font-weight: bold;">Fee Type</th>
                                    <th style="font-weight: bold;">Fee Description</th>
                                    <th style="font-weight: bold;">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total = 0;
                                if (!empty($feeItems)):
                                    $count = 1;
                                    foreach ($feeItems as $feeItem): ?>
                                        <tr>
                                            <td><?= $count ?></td>
                                            <td><?=
                                                array_key_exists('type', $feeItem) ? $feeItem['type'] : 'ADMINISTRATIVE'
                                                ?></td>
                                            <td><?= $feeItem['desc'] ?></td>
                                            <td><?= Yii::$app->formatter->asCurrency($feeItem['amount']) ?></td>
                                        </tr>
                                        <?php
                                        $total += $feeItem['amount'];
                                        $count++;
                                    endforeach;
                                endif; ?>
                                <tr>
                                    <td colspan="3">TOTAL</td>
                                    <td><?= Yii::$app->formatter->asCurrency($total) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
$billingUrl = '';
if ($invoiceFor === 'semesterRegistration') {
    $billingUrl = Url::to(['/semester-session-progress/join-session']);
} elseif ($invoiceFor === 'courseRegistration') {
    $billingUrl = Url::to(['/bill/make-payment']);
}
$payableFeesJson = Json::encode($payableFees);
$payJs = <<< JS
const billingUrl = '$billingUrl';
const payableFees = '$payableFeesJson';

const paymentLoader = $('.prog-charges > .loader');
paymentLoader.html(loader);
paymentLoader.hide();
const paymentErrorDisplay =  $('.prog-charges > .error-display');
paymentErrorDisplay.hide();

$('#pay').click(function (e){
    e.preventDefault();
    if(confirm('Do you want to confirm this payment?')){
        paymentErrorDisplay.hide();
        paymentLoader.show();
        $.ajax({
            url: billingUrl,
            type: 'POST',
            data: {
                'payableFees': payableFees
            }
        }).done(function (data){
            paymentLoader.hide();
             if(!data.success){
                paymentErrorDisplay.html(data.message) 
                paymentErrorDisplay.show();
             }
        }).fail(function (data){
             paymentLoader.hide();
             paymentErrorDisplay.html(data.responseText) 
             paymentErrorDisplay.show();
        });
    }
});
JS;
$this->registerJs($payJs, yii\web\View::POS_READY);





