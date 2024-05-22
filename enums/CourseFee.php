<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/22/2024
 * @time: 11:26 AM
 */

namespace app\enums;

enum CourseFee: string
{
    // Available course registration types
    // Note that these types MUST MATCH what is in the database
    case FA = 'FA';
    case SUPP = 'SUPP';
    case RETAKE = 'RETAKE';
    case SPECIAL = 'SPECIAL';
    case PROJECT = 'PROJECT';

    // This is not a registration type but a block charge for programs billed per semester
    case TUITION = 'TUITION';

    // Available descriptions from the fee items table for above charges
    // Note that these descriptions MUST MATCH what is in the database
    public function feeDescription(): string
    {
        return match ($this) {
            self::FA => 'FIRST ATTEMPT',
            self::SUPP => 'SUPPLEMENTARY FEES',
            self::RETAKE => 'RETAKE',
            self::SPECIAL => 'SPECIAL',
            self::PROJECT => 'PROJECT',
            self::TUITION => 'TUITION FEES'
        };
    }
}