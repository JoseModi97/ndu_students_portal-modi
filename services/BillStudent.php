<?php

namespace app\services;

use app\enums\ChargeFrequency;
use app\enums\CourseFee;
use app\enums\FeePriority;
use app\enums\FeeStatus;
use app\enums\FeeType;
use JetBrains\PhpStorm\ArrayShape;
use yii\db\Query;

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 10:27 AM
 */
final class BillStudent
{
    public function __construct(private readonly StudentToBill $student)
    {
    }

    /**
     * @return array
     */
    #[ArrayShape(['adminCharges' => "array", 'total' => "int"])]
    public function totalAdminFees(): array
    {
        $adminFees = $this->fees(FeeType::ADMIN->value);
        $total = 0;
        $adminCharges = [];

        // Some fees e.g. caution money are charged only once in a student's life. We bill these at 1st year semester 1
        // Note that some fees are charged once but not needed to be billed in the course of a student's progression journey.
        // Fees like gown and cap during graduation. We take note of these types and assign them a priority of 2.
        // We assume that these will be charged outside this work flow.
        foreach ($adminFees as $adminFee) {
            if ($adminFee['frequency'] === ChargeFrequency::ONCE->value && $this->student->level === 1 && $this->student->isInAFirstSemester) {
                $total += $adminFee['amount_charged'];
                $adminCharges[] = [
                    $adminFee['fee_description'] => $adminFee['amount_charged']
                ];
            }
        }

        foreach ($adminFees as $adminFee) {
            if ($this->student->isBilledAnnually) {
                if ($this->student->isInAFirstSemester) {
                    if ($adminFee['frequency'] === ChargeFrequency::ANNUAL->value) {
                        $adminCharges[] = [
                            $adminFee['fee_description'] => $adminFee['amount_charged']
                        ];

                        $total += $adminFee['amount_charged'];
                    }
                } else {
                    $total = 0;
                }
            } else {
                if ($adminFee['frequency'] === ChargeFrequency::SEMESTER->value) {
                    $adminCharges[] = [
                        $adminFee['fee_description'] => $adminFee['amount_charged']
                    ];

                    $total += $adminFee['amount_charged'];
                }
            }
        }

        return [
            'adminCharges' => $adminCharges,
            'total' => $total
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape(['courseCharges' => "array", 'total' => "int"])]
    public function totalCourseFees(): array
    {
        $fees = $this->fees(FeeType::COURSE->value);

        $tempFees = [];
        foreach ($fees as $fee) {
            $tempFees[$fee['fee_description']] = $fee['amount_charged'];
        }

        $courses = [
            [
                'code' => 'SMA101',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA102',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA103',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA104',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA105',
                'type' => 'PROJECT'
            ]
        ];

        $totalUnitAmount = 0;
        $tuitionAmount = 0;
        $courseCharges = [];
        foreach ($courses as $course) {
            // To always make sure that the course coming in can be billed, only allow students to register for units that
            // have their charges already defined
            $courseFee = CourseFee::tryFrom($course['type']);
            $unitAmount = $tempFees[$courseFee->feeDescription()];
            $courseCharges[] = [
                'code' => $course['code'],
                'type' => $course['type'],
                'amount' => $unitAmount
            ];
            $totalUnitAmount += $unitAmount;
        }

        if (!$this->student->isBilledAnnually) {
            try {
                $tuitionAmount = (int)$tempFees[CourseFee::tryFrom('TUITION')->feeDescription()];
                $courseCharges['tuition'] = [
                    'code' => 'TUITION',
                    'type' => 'TUITION',
                    'amount' => $tuitionAmount
                ];
            } catch (\Exception $ex) {
                // Fail silently. We want the student to proceed with course registration.
                // If a tuition charge is not defined, default it to zero. We shall reconcile later
            }
        }

        return [
            'courseCharges' => $courseCharges,
            'total' => $totalUnitAmount + $tuitionAmount
        ];
    }

    /**
     * @param string $feeType
     * @return array
     */
    private function fees(string $feeType): array
    {
        $fees = (new Query())->select([
            'fi.fee_description',
            'pcc.amount_charged',
            'pcc.level_of_study',
            'pcc.semester',
            'bf.name as frequency'
        ])
            ->from('smisportal.fss_prog_curr_charges pcc')
            ->innerJoin('smisportal.fss_fee_items fi', 'fi.fee_code=pcc.fee_code')
            ->innerJoin('smisportal.fss_billing_frequency bf', 'bf.billing_frequency_id=pcc.billing_frequency_id')
            ->where([
                'pcc.prog_curr_id' => $this->student->progCurrId,
                'pcc.acad_session_id' => $this->student->academicSessionId,
                'fi.fee_type' => $feeType,
                'fi.priority' => FeePriority::PRIORITY_1->value,
                'fi.publish' => FeeStatus::PUBLISHED->value
            ]);

        if (!$this->student->isBilledAnnually) {
            $fees->andWhere([
                'pcc.level_of_study' => $this->student->level,
                'pcc.semester' => $this->student->semester
            ]);
        }

        return $fees->all();
    }
}