<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2024
 * @time: 10:14 AM
 */

namespace app\enums;

enum BillingType: string
{
    case NON_INTEGRATED = 'Non-integrated';
    case REGULAR_INTEGRATED = 'Regular-Integrated';
}