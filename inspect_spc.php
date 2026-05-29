<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/config/constants.php';

$config = require __DIR__ . '/config/db.php';
try {
    $db = Yii::createObject($config);
    $db->open();
    
    $table = 'smisportal.sm_student_programme_curriculum';
    $schema = $db->getTableSchema($table);
    
    if ($schema) {
        echo "Table $table exists.\n";
        echo "Primary Key: " . implode(', ', $schema->primaryKey) . "\n";
        $count = $db->createCommand("SELECT count(*) FROM $table")->queryScalar();
        echo "Row count: $count\n";
        
        $col = $schema->columns['student_prog_curriculum_id'];
        echo "Column Type: " . $col->dbType . "\n";
        echo "Is Identity: " . ($col->isPrimaryKey ? 'Yes' : 'No') . "\n";
        
        // Check for sequence or identity
        try {
            $isIdentity = $db->createCommand("SELECT is_identity FROM information_schema.columns WHERE table_schema = 'smisportal' AND table_name = 'sm_student_programme_curriculum' AND column_name = 'student_prog_curriculum_id'")->queryScalar();
            echo "Is Identity (Info Schema): " . ($isIdentity === 'YES' ? 'Yes' : 'No') . "\n";
        } catch (\Exception $e) {}
    } else {
        echo "Table $table NOT FOUND.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
