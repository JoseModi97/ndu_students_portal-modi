<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

// Manually instantiate the module
$module = new \app\modules\refund_requests\Module('refund-requests');
$module->overrideEligibility = false;

// Mock the controller
$controller = new \app\modules\refund_requests\controllers\DefaultController('default', $module);

$regNo = 'NR605/0001/2022';

$spc = (new \yii\db\Query())
    ->select(['*'])
    ->from('smis.sm_student_programme_curriculum')
    ->where(['registration_number' => $regNo])
    ->one(Yii::$app->smisDb);

if (!$spc) {
    echo "Registration number $regNo not found in SMIS\n";
    exit;
}

$admRefNo = $spc['adm_refno'];
$user = \app\modules\refund_requests\models\User::find()->where(['adm_refno' => $admRefNo])->one();

if (!$user) {
    echo "User not found in portal for adm_refno $admRefNo\n";
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
