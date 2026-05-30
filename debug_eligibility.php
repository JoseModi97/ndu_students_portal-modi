<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

// Manually instantiate the module
$module = new \app\modules\refund_requests\Module('refund-requests');
$module->overrideCautionFee = false;

// Mock the controller
$controller = new \app\modules\refund_requests\controllers\DefaultController('default', $module);

$regNo = 'ND601/0007/2022';

$spc = \app\models\StudentProgCurriculum::find()->where(['registration_number' => $regNo])->one();

if (!$spc) {
    echo "Registration number $regNo not found\n";
    exit;
}

$user = \app\modules\refund_requests\models\User::findOne($spc->adm_refno);

if (!$user) {
    echo "User not found\n";
    exit;
}

echo "Testing for user: " . $regNo . " (adm_refno: " . $user->adm_refno . ")\n";
echo "Clearance status: '" . $user->clearance_status . "'\n";

// Access private method checkEligibility via reflection
$reflection = new \ReflectionClass($controller);
$method = $reflection->getMethod('checkEligibility');
$method->setAccessible(true);

$result = $method->invoke($controller, $user);

echo "Eligibility Result:\n";
print_r($result);

echo "\nModule overrideCautionFee in script: " . ($module->overrideCautionFee ? 'TRUE' : 'FALSE') . "\n";

$progCurrId = $result['prog_curriculum_id'];
if ($progCurrId) {
    $cautionCharge = (new \yii\db\Query())
        ->from('smis.fss_prog_curr_charges pcc')
        ->innerJoin('smis.fss_fee_items fi', 'pcc.fee_code = fi.fee_code')
        ->where(['pcc.prog_curr_id' => $progCurrId])
        ->andWhere(['ILIKE', 'fi.fee_description', '%CAUTION%'])
        ->all(Yii::$app->smisDb);
    echo "\nCaution charges found in SMIS by description:\n";
    print_r($cautionCharge);
}
