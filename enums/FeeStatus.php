<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/22/2024
 * @time: 9:15 AM
 */

namespace app\enums;

enum FeeStatus: string
{
    case PUBLISHED = 'YES';
    case NOT_PUBLISHED = 'NO';
}