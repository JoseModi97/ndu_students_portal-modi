<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$smisDb = require __DIR__ . '/smis_db.php';
$secretsFile = __DIR__ . '/secrets.local.php';
$localSecrets = is_file($secretsFile) ? require $secretsFile : [];
$secret = static function (string $environmentKey, string $localKey) use ($localSecrets): string {
    $environmentValue = getenv($environmentKey);
    $value = $environmentValue !== false ? $environmentValue : ($localSecrets[$localKey] ?? '');
    if (!is_string($value) || trim($value) === '') {
        throw new \yii\base\InvalidConfigException("Missing required application secret: {$environmentKey}.");
    }

    return $value;
};

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@views' => '@app/views',
        '@regDocsUploadUrl' => '@app/uploads/registration/',
        '@changeNameDocsUploadDir' => '@app/uploads/change_name/',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => $secret('APP_COOKIE_VALIDATION_KEY', 'cookieValidationKey'),
            'enableCookieValidation' => true,
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => true,
                'sameSite' => 'Lax',
            ],
        ],
        'response' => [
            'on beforeSend' => static function (\yii\base\Event $event): void {
                /** @var \yii\web\Response $response */
                $response = $event->sender;
                $headers = $response->headers;
                if (!$headers->has('X-Frame-Options')) {
                    $headers->set('X-Frame-Options', 'SAMEORIGIN');
                }
                if (!$headers->has('X-Content-Type-Options')) {
                    $headers->set('X-Content-Type-Options', 'nosniff');
                }
                if (!$headers->has('Referrer-Policy')) {
                    $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
                }
                if (!$headers->has('Permissions-Policy')) {
                    $headers->set('Permissions-Policy', 'camera=(), geolocation=(), microphone=()');
                }
                if (Yii::$app->request->isSecureConnection && !$headers->has('Strict-Transport-Security')) {
                    $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
                }
            },
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'useStrictMode' => true,
            'cookieParams' => [
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => true,
            'authTimeout' => 3600
        ],
        'ldapAuth' => [
            'class' => 'app\components\LdapAuth',
            'host' => 'dc1.ad.uonbi.ac.ke',
            'port' => 636,
            'protocol' => 'ldaps://',
            'baseDn' => 'DC=AD,DC=UONBI,DC=AC,DC=KE',
            'searchUserName' => 'CN=pwdappuser,CN=Users,DC=AD,DC=UONBI,DC=AC,DC=KE',
            'searchUserPassword' => $secret('LDAP_SEARCH_USER_PASSWORD', 'ldapSearchUserPassword'),
            'ldapVersion' => 3,
            'followReferrals' => false,
            'timeout' => 10,
            'connectTimeout' => 10
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            /**
             * https://github.com/symfony/symfony-docs/issues/17115
             */
            'class' => Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'dsn' => $secret('MAILER_DSN', 'mailerDsn'),
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'smisDb' => $smisDb,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'home' => '/site/index',
                'login' => '/site/login',
                'logout' => '/site/logout',
                '<controller>/<action:(report-lost-id|print-id)>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'formatter' => [
            'defaultTimeZone' => 'Africa/Nairobi',
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s',
            'thousandSeparator' => ',',
            'currencyCode' => 'KES',
        ],
        'assetManager' => [
            /**
             * Yii loads assets from locally installed directories.
             * To try and improve on performance, we want to load these assets from CDNs where possible.
             */
            'appendTimestamp' => true,
            'forceCopy' => YII_DEBUG,
//            'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js',
                    ]
                ],
                'yii\jui\JuiAsset' => [
                    'css' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css'
                    ],
                    'js' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'
                    ]
                ],
                'yii\bootstrap5\BootstrapAsset' => [
                    'css' => [
                        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap5\BootstrapPluginAsset' => [
                    'js' => [
                        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js'
                    ],
                    'depends' => [
                        'yii\jui\JuiAsset',
                    ]
                ],
                /**
                 * Yii comes with some js assets under vendor/yiisoft/yii2/assets
                 * To improve on performance, we combine and minify these files
                 */
                'yii\web\YiiAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\assets\AllYiiAssets']
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\assets\AllYiiAssets']
                ],
                'yii\validators\ValidationAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\assets\AllYiiAssets']
                ],
                'yii\grid\GridViewAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\assets\AllYiiAssets']
                ],
                ' yii\captcha\CaptchaAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\assets\AllYiiAssets']
                ]
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        'ecitizen' => require __DIR__ . '/../modules/ecitizen/config/module.php',
	'caution-refund' => ['class' => 'app\modules\caution_refund\Module'],
	'refund-requests' => ['class' => 'app\modules\refund_requests\Module'],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
