<?php
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$schema = Yii::$app->db->getTableSchema('smisportal.sm_student_status');
if ($schema) {
    print_r($schema->columnNames);
} else {
    echo "Table not found.\n";
}
