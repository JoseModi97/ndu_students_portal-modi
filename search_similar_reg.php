<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

echo "Searching for registration numbers like NR605/0001% ...\n";

$students = (new \yii\db\Query())
    ->select(['*'])
    ->from('smis.sm_student_programme_curriculum')
    ->where(['LIKE', 'registration_number', 'NR605/0001%', false])
    ->all(Yii::$app->smisDb);

print_r($students);
