<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/22/2024
 * @time: 10:40 AM
 */

namespace app\enums;

enum ChargeFrequency: string
{
    case ONCE = 'ONCE';
    case ANNUAL = 'ANNUAL';
    case SEMESTER = 'SEMESTER';
    case UNIT = 'UNIT';
}