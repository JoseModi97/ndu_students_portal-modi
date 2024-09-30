<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/23/2024
 * @time: 10:24 PM
 */

namespace app\enums;

enum InvoiceStatus: string
{
    case FIRST = 'FIRST';
    case RECONCILED = 'RECONCILED';
}