<?php

use yii\db\Connection;
use yii\db\Migration;

class m260619_000001_import_ndu_service_codes extends Migration
{
    private const SERVICES = [
        [15247547, 'Diploma Graduation Package.'],
        [15247546, 'Higher Diploma Graduation Package'],
        [15247545, 'Post Graduate Diploma Graduation Package'],
        [15247544, 'Bachelors Graduation Package'],
        [15247543, 'Masters Graduation Package'],
        [15247542, 'PHD Graduation Package'],
        [15247541, 'Credit Waiver'],
        [15247540, 'Credit Transfer'],
        [15247539, 'Exam Remarking'],
        [15247538, 'Repeat Academic Year'],
        [15247537, 'Repeat a Unit'],
        [15247536, 'Supplementary Examination'],
        [15247535, 'CUE Equation for Allied Student'],
        [15247534, 'Certificate Storage Fee'],
        [15247533, 'Replacement of lost, Damaged or Stolen Certificate'],
        [15247532, 'Certificate Correction'],
        [15247531, 'Transcripts/Certificates Verification &Certification'],
        [15247530, 'Replacement of Lost Damaged or Stolen Transcripts'],
        [15247529, 'Property Damage Penalty'],
        [15247528, 'Gown Replacement Fee (Lost Gown)'],
        [15247527, 'Gown Late Return Fee'],
        [15247526, 'Student Reinstatement (Overstay)'],
        [15245097, 'Masters Tuition Fee 2025/2026 Sem 1'],
        [15245096, 'PhD Tuition Fee 2025/2026 Sem 1'],
        [15240693, 'PhD in Crisis Response and Disaster Management'],
        [15240692, 'Masters of Arts in Gender and Peace Support Studies'],
        [15239274, 'Semester 1 Fees'],
        [15239273, 'Supplementary Exam Application'],
        [15239272, 'Semester 4 Fees'],
        [15239271, 'Semester 3 Fees'],
        [15239270, 'Semester 2 Fees'],
        [15239269, 'Master of Arts in Crisis Response and Disaster Management'],
    ];

    public function safeUp()
    {
        $this->importServices(Yii::$app->db, 'smisportal.fss_payment_types');
        $this->importServices(Yii::$app->getModule('ecitizen')->getSmisDb(), 'smis.fss_payment_types');
    }

    public function safeDown()
    {
        echo "m260619_000001_import_ndu_service_codes cannot be safely reverted.\n";
        return false;
    }

    private function importServices(Connection $db, string $table): void
    {
        foreach (self::SERVICES as $priority => [$paymentTypeId, $description]) {
            $db->createCommand(
                "insert into {$table} (payment_type_id, payment_desc, order_priority)
                 select cast(:payment_type_id as bigint),
                        cast(:payment_desc as varchar),
                        cast(:order_priority as integer)
                  where not exists (
                      select 1
                        from {$table}
                       where payment_type_id = cast(:payment_type_id as bigint)
                          or lower(trim(payment_desc)) = lower(trim(cast(:payment_desc as varchar)))
                  )",
                [
                    ':payment_type_id' => $paymentTypeId,
                    ':payment_desc' => $description,
                    ':order_priority' => $priority + 1,
                ]
            )->execute();
        }
    }
}
