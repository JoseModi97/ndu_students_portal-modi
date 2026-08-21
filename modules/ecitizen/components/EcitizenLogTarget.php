<?php

namespace app\modules\ecitizen\components;

use yii\log\FileTarget;
use yii\log\Logger;

/**
 * Dedicated text log target for the eCitizen module.
 *
 * Every entry is wrapped in a clearly delimited, timestamped block so the
 * plain .txt file reads well when opened directly by a developer, rather
 * than as a single dense line per message (Yii's default FileTarget style).
 */
final class EcitizenLogTarget extends FileTarget
{
    public function formatMessage($message)
    {
        [$text, $level, $category, $timestamp] = $message;

        if (!is_string($text)) {
            $text = $text instanceof \Throwable ? (string) $text : \yii\helpers\VarDumper::export($text);
        }

        $seconds = (int) $timestamp;
        $milliseconds = (int) round(($timestamp - $seconds) * 1000);
        $time = date('Y-m-d H:i:s', $seconds) . sprintf('.%03d', $milliseconds);
        $levelName = strtoupper(Logger::getLevelName($level));
        $rule = str_repeat('-', 88);

        return sprintf(
            "%s\n[%s] [%-7s] [%s]\n%s\n",
            $rule,
            $time,
            $levelName,
            $category,
            $text
        );
    }
}
