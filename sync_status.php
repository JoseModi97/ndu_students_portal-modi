<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
try {
    // Set to ACTIVE (1) to match SMIS
    Yii::$app->db->createCommand()->update('smisportal.sm_student_programme_curriculum', 
        ['status_id' => 1], 
        ['registration_number' => $regNo]
    )->execute();
    echo "Portal status for $regNo synchronized to ACTIVE.\n";
} catch (\Exception $e) {
    echo "Error synchronizing status: " . $e->getMessage() . "\n";
}
