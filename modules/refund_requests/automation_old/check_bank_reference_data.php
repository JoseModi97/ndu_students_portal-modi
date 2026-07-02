<?php
/**
 * Checks FSS bank and branch reference data counts on SMIS and Portal.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$tables = [
    'fss_banks' => 'brank_id',
    'fss_bank_branches' => 'branch_id',
];

foreach ($tables as $table => $pk) {
    $smisCount = (new \yii\db\Query())
        ->from("smis.$table")
        ->count('*', Yii::$app->smisDb);

    $portalCount = (new \yii\db\Query())
        ->from("smisportal.$table")
        ->count('*', Yii::$app->db);

    $smisMax = (new \yii\db\Query())
        ->select("MAX($pk)")
        ->from("smis.$table")
        ->scalar(Yii::$app->smisDb);

    $portalMax = (new \yii\db\Query())
        ->select("MAX($pk)")
        ->from("smisportal.$table")
        ->scalar(Yii::$app->db);

    echo "$table\n";
    echo "  SMIS count: $smisCount, max $pk: " . ($smisMax ?: 'NULL') . "\n";
    echo "  Portal count: $portalCount, max $pk: " . ($portalMax ?: 'NULL') . "\n";
    echo "  Status: " . ((int)$smisCount === (int)$portalCount && (string)$smisMax === (string)$portalMax ? 'MATCH' : 'CHECK') . "\n\n";
}
