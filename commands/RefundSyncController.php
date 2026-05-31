<?php

namespace app\commands;

use app\modules\refund_requests\models\RefundRequest;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Handles synchronization of refund requests from Portal to SMIS database.
 */
class RefundSyncController extends Controller
{
    /**
     * Synchronizes pending refund requests to SMIS.
     * @return int
     */
    public function actionSync()
    {
        $this->stdout("Starting refund requests synchronization...\n", Console::FG_CYAN);

        $pendingRequests = RefundRequest::find()
            ->where(['sync_status' => 0])
            ->all();

        if (empty($pendingRequests)) {
            $this->stdout("No pending requests to synchronize.\n", Console::FG_GREEN);
            return ExitCode::OK;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($pendingRequests as $request) {
            $this->stdout("Syncing Request ID: {$request->request_id}... ");

            $module = Yii::$app->getModule('refund-requests');
            $smisDb = $module ? $module->getSmisDb() : Yii::$app->smisDb;

            $transaction = $smisDb->beginTransaction();
            try {
                $exists = (new \yii\db\Query())
                    ->from('smis.fss_refund_requests')
                    ->where(['request_id' => $request->request_id])
                    ->exists($smisDb);

                $attributes = $request->getAttributes();
                unset($attributes['sync_status'], $attributes['sync_error'], $attributes['last_synced_at']);
                unset($attributes['payment_method']);

                if ($exists) {
                    $smisDb->createCommand()
                        ->update('smis.fss_refund_requests', $attributes, ['request_id' => $request->request_id])
                        ->execute();
                } else {
                    $smisDb->createCommand()
                        ->insert('smis.fss_refund_requests', $attributes)
                        ->execute();
                }

                $transaction->commit();

                $request->updateAttributes([
                    'sync_status' => 1,
                    'sync_error' => null,
                    'last_synced_at' => date('Y-m-d H:i:s'),
                ]);

                $this->stdout("OK\n", Console::FG_GREEN);
                $successCount++;
            } catch (\Exception $e) {
                $transaction->rollBack();
                $request->updateAttributes([
                    'sync_status' => 2,
                    'sync_error' => $e->getMessage(),
                ]);
                $this->stderr("FAILED: " . $e->getMessage() . "\n", Console::FG_RED);
                $failureCount++;
            }
        }

        $this->stdout("\nSynchronization completed.\n", Console::FG_CYAN);
        $this->stdout("Success: $successCount\n", Console::FG_GREEN);
        if ($failureCount > 0) {
            $this->stdout("Failed: $failureCount\n", Console::FG_RED);
        }

        return ExitCode::OK;
    }
}
