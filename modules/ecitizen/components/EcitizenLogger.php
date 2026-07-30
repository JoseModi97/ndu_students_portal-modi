<?php

namespace app\modules\ecitizen\components;

use Throwable;
use Yii;
use yii\web\HttpException;

/**
 * Central logging facade for the eCitizen module.
 *
 * Every call here is written (via EcitizenLogTarget, registered by
 * {@see \app\modules\ecitizen\Module::init()}) to the module's own
 * runtime/logs/ecitizen/ecitizen.txt file, in a developer-readable format:
 * request/action context, the acting user, and - for errors - the full
 * exception class, message, source location and stack trace.
 */
final class EcitizenLogger
{
    private const CATEGORY_REQUEST = 'ecitizen.module';
    private const CATEGORY_ERROR = 'ecitizen.payment';

    private const SENSITIVE_KEYS = ['password', 'secret', 'apikey', 'api_key', 'token', 'authorization', 'hash', 'securehash'];

    /**
     * Logs the start of a module request/action and returns a start time
     * to hand back to {@see requestEnd()} or {@see exception()} so elapsed
     * time can be reported.
     */
    public static function requestStart(string $action, array $params = []): float
    {
        $lines = [
            'Action  : ' . $action,
            'HTTP    : ' . self::httpLine(),
            'User    : ' . self::userLine(),
        ];
        if ($params !== []) {
            $lines[] = 'Params  : ' . self::safeJson(self::redact($params));
        }

        Yii::info(implode("\n", $lines), self::CATEGORY_REQUEST);

        return microtime(true);
    }

    public static function requestEnd(string $action, float $startedAt, mixed $result = null): void
    {
        $lines = [
            'Action  : ' . $action . ' completed in ' . self::elapsedMs($startedAt) . ' ms',
        ];
        if (is_array($result) && array_key_exists('success', $result)) {
            $lines[] = 'Result  : ' . ($result['success'] ? 'success' : 'failure')
                . (isset($result['message']) ? ' - ' . $result['message'] : '');
        }

        Yii::info(implode("\n", $lines), self::CATEGORY_REQUEST);
    }

    /**
     * Logs a full, developer-oriented exception report: class, message,
     * code, file:line, request/user context and the complete stack trace.
     * HTTP client errors (4xx) are logged at warning level, everything
     * else at error level.
     */
    public static function exception(string $action, Throwable $exception, ?float $startedAt = null, array $context = []): void
    {
        $statusCode = $exception instanceof HttpException ? $exception->statusCode : 500;
        $isClientError = $exception instanceof HttpException && $statusCode >= 400 && $statusCode < 500;

        $lines = [
            'Action    : ' . $action . ($startedAt !== null ? ' (' . self::elapsedMs($startedAt) . ' ms)' : ''),
            'Exception : ' . get_class($exception),
            'Message   : ' . $exception->getMessage(),
            'Code      : ' . $exception->getCode()
                . ($exception instanceof HttpException ? ' (HTTP ' . $statusCode . ')' : ''),
            'Location  : ' . $exception->getFile() . ':' . $exception->getLine(),
            'HTTP      : ' . self::httpLine(),
            'User      : ' . self::userLine(),
        ];
        if ($context !== []) {
            $lines[] = 'Context   : ' . self::safeJson(self::redact($context));
        }

        $previous = $exception->getPrevious();
        if ($previous !== null) {
            $lines[] = 'Caused by : ' . get_class($previous) . ' - ' . $previous->getMessage();
        }

        $lines[] = 'Stack trace:';
        $lines[] = $exception->getTraceAsString();

        $message = implode("\n", $lines);
        if ($isClientError) {
            Yii::warning($message, self::CATEGORY_ERROR);
        } else {
            Yii::error($message, self::CATEGORY_ERROR);
        }
    }

    public static function warning(string $message, array $context = []): void
    {
        Yii::warning(self::withContext($message, $context), self::CATEGORY_ERROR);
    }

    public static function error(string $message, array $context = []): void
    {
        Yii::error(self::withContext($message, $context), self::CATEGORY_ERROR);
    }

    private static function withContext(string $message, array $context): string
    {
        $lines = [
            'Message : ' . $message,
            'HTTP    : ' . self::httpLine(),
            'User    : ' . self::userLine(),
        ];
        if ($context !== []) {
            $lines[] = 'Context : ' . self::safeJson(self::redact($context));
        }

        return implode("\n", $lines);
    }

    private static function elapsedMs(float $startedAt): float
    {
        return round((microtime(true) - $startedAt) * 1000, 1);
    }

    private static function httpLine(): string
    {
        if (!Yii::$app->has('request', true)) {
            return 'console';
        }

        try {
            $request = Yii::$app->request;
            if (!$request instanceof \yii\web\Request) {
                return 'console';
            }

            return $request->method . ' ' . $request->url . ($request->isAjax ? ' (ajax)' : '');
        } catch (Throwable) {
            return 'unknown';
        }
    }

    private static function userLine(): string
    {
        if (!Yii::$app->has('user', true) || !Yii::$app->has('request', true)) {
            return 'console';
        }

        try {
            $ip = Yii::$app->request instanceof \yii\web\Request ? Yii::$app->request->userIP : 'n/a';
            $user = Yii::$app->user;
            if ($user->isGuest) {
                return 'guest | ip=' . ($ip ?: 'n/a');
            }

            $admRefNo = $user->identity->adm_refno ?? 'n/a';

            return 'adm_refno=' . $admRefNo . ' | ip=' . ($ip ?: 'n/a');
        } catch (Throwable) {
            return 'unknown';
        }
    }

    private static function redact(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::redact($value);
                continue;
            }

            foreach (self::SENSITIVE_KEYS as $needle) {
                if (stripos((string) $key, $needle) !== false) {
                    $data[$key] = '***redacted***';
                    break;
                }
            }
        }

        return $data;
    }

    private static function safeJson(array $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES);

        return $json === false ? '[unserializable]' : $json;
    }
}
