<?php
/**
 * Restores the Portal bank and bank-branch reference data from SMIS.
 *
 * This script is idempotent: existing rows are updated and missing rows are
 * inserted using the same primary keys as the SMIS source tables.
 */

$root = __DIR__;
while (!is_file($root . '/vendor/autoload.php')) {
    $parent = dirname($root);
    if ($parent === $root) {
        throw new RuntimeException('Could not locate project root from automation script.');
    }
    $root = $parent;
}

require $root . '/vendor/autoload.php';
require $root . '/vendor/yiisoft/yii2/Yii.php';

$config = require $root . '/config/console.php';
new yii\console\Application($config);

$tables = [
    'fss_banks' => [
        'primaryKey' => 'brank_id',
        'columns' => ['brank_id', 'bank_code', 'bank_name', 'status'],
    ],
    'fss_bank_branches' => [
        'primaryKey' => 'branch_id',
        'columns' => ['branch_id', 'branch_code', 'branch_name', 'bank_code'],
    ],
];

$portalDb = Yii::$app->db;
$smisDb = Yii::$app->smisDb;
$transaction = $portalDb->beginTransaction();

try {
    foreach ($tables as $table => $definition) {
        $sourceTable = 'smis.' . $table;
        $targetTable = 'smisportal.' . $table;
        $primaryKey = $definition['primaryKey'];

        $sourceSchema = $smisDb->getTableSchema($sourceTable, true);
        $targetSchema = $portalDb->getTableSchema($targetTable, true);
        if ($sourceSchema === null || $targetSchema === null) {
            throw new RuntimeException("Required reference table is missing: $sourceTable or $targetTable.");
        }

        $columns = array_values(array_intersect(
            $definition['columns'],
            $sourceSchema->columnNames,
            $targetSchema->columnNames
        ));
        if (!in_array($primaryKey, $columns, true)) {
            throw new RuntimeException("Primary key $primaryKey is unavailable for $table.");
        }

        $rows = (new yii\db\Query())
            ->select($columns)
            ->from($sourceTable)
            ->orderBy([$primaryKey => SORT_ASC])
            ->all($smisDb);

        if (!$rows) {
            throw new RuntimeException(
                "SMIS source table $sourceTable is empty; refusing to erase or fabricate reference data."
            );
        }

        $existingIds = array_flip((new yii\db\Query())
            ->select($primaryKey)
            ->from($targetTable)
            ->column($portalDb));
        $inserted = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $isExisting = isset($existingIds[(string)$row[$primaryKey]]);
            $portalDb->createCommand()
                ->upsert($targetTable, $row, true)
                ->execute();

            $isExisting ? $updated++ : $inserted++;
        }

        resetSequence($portalDb, $targetTable, $primaryKey);
        echo "$table: inserted $inserted, refreshed $updated from SMIS.\n";
    }

    $transaction->commit();
    echo "SUCCESS: Portal bank reference data is ready.\n";
} catch (Throwable $e) {
    $transaction->rollBack();
    fwrite(STDERR, 'ERROR: ' . $e->getMessage() . "\n");
    exit(1);
}

/**
 * Keeps a PostgreSQL identity/serial sequence ahead of explicitly seeded IDs.
 */
function resetSequence(yii\db\Connection $db, string $table, string $primaryKey): void
{
    $sequence = $db->createCommand(
        'SELECT pg_get_serial_sequence(:table, :column)',
        [':table' => $table, ':column' => $primaryKey]
    )->queryScalar();

    if (!$sequence) {
        return;
    }

    $maxId = (new yii\db\Query())
        ->select("MAX({{{$primaryKey}}})")
        ->from($table)
        ->scalar($db);

    if ($maxId !== null) {
        $db->createCommand(
            'SELECT setval(CAST(:sequence AS regclass), :value, true)',
            [':sequence' => $sequence, ':value' => (int)$maxId]
        )->execute();
    }
}
