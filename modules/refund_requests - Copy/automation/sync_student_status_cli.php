<?php
/**
 * Student Status Manager CLI
 * 
 * Synchronizes and manages student status and clearance across SMIS and SMIS Portal.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

function prompt($text) {
    echo $text . ': ';
    return trim(fgets(STDIN));
}

/**
 * Fetches student data from both SMIS and Portal
 */
function getStudentData($regNo) {
    // Authoritative SMIS Data
    $smisData = (new \yii\db\Query())
        ->select(['spc.status_id', 's.status', 'spc.adm_refno', 'ad.clearance_status'])
        ->from('smis.sm_student_programme_curriculum spc')
        ->leftJoin('smis.sm_student_status s', 'spc.status_id = s.status_id')
        ->leftJoin('smis.sm_admitted_student ad', 'spc.adm_refno = ad.adm_refno')
        ->where(['spc.registration_number' => $regNo])
        ->one(Yii::$app->smisDb);

    // Portal Data
    $portalData = (new \yii\db\Query())
        ->select(['spc.status_id', 's.status', 'spc.adm_refno', 'ad.clearance_status'])
        ->from('smisportal.sm_student_programme_curriculum spc')
        ->leftJoin('smisportal.sm_student_status s', 'spc.status_id = s.status_id')
        ->leftJoin('smisportal.sm_admitted_student ad', 'spc.adm_refno = ad.adm_refno')
        ->where(['spc.registration_number' => $regNo])
        ->one();

    return [
        'smis' => $smisData,
        'portal' => $portalData
    ];
}

echo "\n==============================================\n";
echo "    STUDENT STATUS & CLEARANCE SYNC TOOL\n";
echo "==============================================\n\n";

$regNo = prompt("Enter Student Registration Number");

if (empty($regNo)) {
    echo "Error: Registration number is required.\n";
    exit(1);
}

try {
    $clearanceOptions = ['PENDING', 'CLEARED', 'NOT CLEARED'];
    
    while (true) {
        $data = getStudentData($regNo);

        if (!$data['smis']) {
            echo "Error: Student $regNo not found in SMIS Database.\n";
            exit(1);
        }

        echo "\n----------------------------------------------------------------------------------------------------\n";
        echo " STUDENT: $regNo\n";
        echo "----------------------------------------------------------------------------------------------------\n";
        
        // Column padding
        $c1 = 35; // Academic Status
        $c2 = 35; // University Clearance
        $c3 = 25; // Empty column

        echo str_pad("[1] ACADEMIC STATUS", $c1) . " | " . str_pad("[2] UNIVERSITY CLEARANCE", $c2) . " | " . str_pad("", $c3) . "\n";
        echo str_repeat("-", $c1) . "-|-" . str_repeat("-", $c2) . "-|-" . str_repeat("-", $c3) . "\n";
        
        // Academic Status Strings
        $smisStatus = ($data['smis']['status'] ?? 'UNKNOWN') . " (ID: " . ($data['smis']['status_id'] ?? 'N/A') . ")";
        $portalStatus = ($data['portal']['status'] ?? 'NOT FOUND') . " (ID: " . ($data['portal']['status_id'] ?? 'N/A') . ")";

        // Clearance Status Strings with derived IDs
        $sClearStr = $data['smis']['clearance_status'] ?? 'PENDING';
        $sClearIdx = array_search(strtoupper($sClearStr), $clearanceOptions);
        $sClearId = ($sClearIdx !== false) ? ($sClearIdx + 1) : 'N/A';
        $smisClearance = $sClearStr . " (ID: " . $sClearId . ")";

        $pClearStr = $data['portal']['clearance_status'] ?? 'PENDING';
        $pClearIdx = array_search(strtoupper($pClearStr), $clearanceOptions);
        $pClearId = ($pClearIdx !== false) ? ($pClearIdx + 1) : 'N/A';
        $portalClearance = $pClearStr . " (ID: " . $pClearId . ")";
        
        echo str_pad(" SMIS (Auth): " . $smisStatus, $c1) . " | " . str_pad(" SMIS (Auth): " . $smisClearance, $c2) . " | " . str_pad("", $c3) . "\n";
        echo str_pad(" Portal:      " . $portalStatus, $c1) . " | " . str_pad(" Portal:      " . $portalClearance, $c2) . " | " . str_pad("", $c3) . "\n";
        
        echo "----------------------------------------------------------------------------------------------------\n";
        echo " OPTIONS:\n";
        echo "  [1] Change Academic Status\n";
        echo "  [2] Change University Clearance\n";
        echo "  [0] Exit\n";

        $mainChoice = prompt("\nSelect an option");

        if ($mainChoice === '1') {
            // --- CHANGE ACADEMIC STATUS ---
            $availableStatuses = (new \yii\db\Query())
                ->from('smis.sm_student_status')
                ->orderBy(['status_id' => SORT_ASC])
                ->all(Yii::$app->smisDb);

            echo "\nAVAILABLE ACADEMIC STATUSES:\n";
            echo " [ 0] CANCEL\n";
            foreach ($availableStatuses as $s) {
                printf(" [%2d] %s\n", $s['status_id'], $s['status']);
            }

            $newStatusId = prompt("\nEnter New Status ID");

            if ($newStatusId !== "0" && !empty($newStatusId)) {
                $targetStatus = null;
                foreach ($availableStatuses as $s) {
                    if ($s['status_id'] == $newStatusId) {
                        $targetStatus = $s['status'];
                        break;
                    }
                }

                if (!$targetStatus) {
                    echo "Error: Invalid Status ID.\n";
                } else {
                    echo "\nSynchronizing Academic Status to '$targetStatus'...\n";
                    
                    $tSmis = Yii::$app->smisDb->beginTransaction();
                    $tPortal = Yii::$app->db->beginTransaction();

                    try {
                        // Update SMIS
                        Yii::$app->smisDb->createCommand()->update('smis.sm_student_programme_curriculum',
                            ['status_id' => $newStatusId],
                            ['registration_number' => $regNo]
                        )->execute();
                        echo "[OK] SMIS academic status updated.\n";

                        // Find equivalent status ID in Portal
                        $portalStatusId = (new \yii\db\Query())
                            ->select(['status_id'])
                            ->from('smisportal.sm_student_status')
                            ->where(['status' => $targetStatus])
                            ->scalar();

                        if ($portalStatusId) {
                            Yii::$app->db->createCommand()->update('smisportal.sm_student_programme_curriculum',
                                ['status_id' => $portalStatusId],
                                ['registration_number' => $regNo]
                            )->execute();
                            echo "[OK] Portal academic status updated.\n";
                        } else {
                            echo "[!] Warning: Status '$targetStatus' not found in Portal lookup table. Skipping portal update.\n";
                        }

                        $tSmis->commit();
                        $tPortal->commit();
                        echo "SUCCESS: Academic status synchronized.\n";
                    } catch (\Exception $e) {
                        $tSmis->rollBack();
                        $tPortal->rollBack();
                        echo "ERROR: " . $e->getMessage() . "\n";
                    }
                }
            }

        } elseif ($mainChoice === '2') {
            // --- CHANGE UNIVERSITY CLEARANCE ---
            echo "\nSELECT NEW CLEARANCE STATUS:\n";
            echo " [0] CANCEL\n";
            foreach ($clearanceOptions as $idx => $opt) {
                printf(" [%d] %s\n", $idx + 1, $opt);
            }

            $cChoice = prompt("\nEnter option");

            if ($cChoice !== "0" && isset($clearanceOptions[$cChoice - 1])) {
                $newClearance = $clearanceOptions[$cChoice - 1];
                echo "\nSynchronizing Clearance Status to '$newClearance'...\n";

                $tSmis = Yii::$app->smisDb->beginTransaction();
                $tPortal = Yii::$app->db->beginTransaction();

                try {
                    // Update SMIS
                    if ($data['smis']['adm_refno']) {
                        Yii::$app->smisDb->createCommand()->update('smis.sm_admitted_student',
                            ['clearance_status' => $newClearance],
                            ['adm_refno' => $data['smis']['adm_refno']]
                        )->execute();
                        echo "[OK] SMIS clearance status updated.\n";
                    } else {
                        echo "[!] Error: No adm_refno found for student in SMIS.\n";
                    }

                    // Update Portal
                    if ($data['portal']['adm_refno']) {
                        Yii::$app->db->createCommand()->update('smisportal.sm_admitted_student',
                            ['clearance_status' => $newClearance],
                            ['adm_refno' => $data['portal']['adm_refno']]
                        )->execute();
                        echo "[OK] Portal clearance status updated.\n";
                    } else {
                        echo "[!] Warning: No adm_refno found for student in Portal. Skipping portal update.\n";
                    }

                    $tSmis->commit();
                    $tPortal->commit();
                    echo "SUCCESS: Clearance status synchronized.\n";
                } catch (\Exception $e) {
                    $tSmis->rollBack();
                    $tPortal->rollBack();
                    echo "ERROR: " . $e->getMessage() . "\n";
                }
            }

        } elseif ($mainChoice === '0') {
            echo "Exiting Tool. Goodbye!\n";
            break;
        } else {
            echo "Invalid choice. Please try again.\n";
        }
    }
} catch (\Exception $e) {
    echo "\nFATAL ERROR: " . $e->getMessage() . "\n";
}
