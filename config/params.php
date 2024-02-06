<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

return [
    'sitename' => 'smis',
    'sitenameLong' => 'smis',
    'icon-framework' => 'fa',
    'senderName' => 'ndu smis',
    'noReplyEmail' => 'ndukenyadev@uonbi.ac.ke',
    'bsVersion' => '5.x', // this will set globally `bsVersion` to Bootstrap 4.x for all Krajee Extensions
    'verifyEmailUrl' => 'http://localhost:81/ndu_students_portal/web/verify/email',
    // These controllers are accessible even when user profile is incomplete
    'accessibleControllersIfProfileIncomplete' => [
        'account',
        'verify'
    ],
    // These actions are accessible even when user profile is incomplete
    'accessibleActionsIfProfileIncomplete' => [
        'error',
        'logout',
    ]
];
