<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/23/2024
 * @time: 10:27 PM
 */

namespace app\enums;

enum ReceiptStatus: string
{
    case RECEIPTED = 'RECEIPTED';
    case INVOICED = 'INVOICED';
}