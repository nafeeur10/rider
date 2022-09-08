<?php

use Illuminate\Support\Facades\Log;

function attempt(callable $callback, bool $log = true): mixed
{
    try {
        return $callback();
    } catch (Throwable $e) {
        if ($log) {
            Log::error('Failed attempt', ['error' => $e]);
        }

        return null;
    }
}

function isValidMobile($mobile): bool
{
    return preg_match('/^[0-9]{10}+$/', $mobile);
}

function isValidEmail($email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL)
        && preg_match('/@.+\./', $email);
}
