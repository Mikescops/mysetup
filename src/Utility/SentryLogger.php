<?php

namespace App\Utility;

use Sentry;

class SentryLogger
{
    public function logError($exception)
    {
        Sentry\captureException($exception);
    }

    public function logErrorWithUser(\Throwable $exception, $Auth = null)
    {
        if ($Auth && $Auth->user()) {
            $userId = $Auth->user('id');
            $username = $Auth->user('name');

            Sentry\configureScope(function (Sentry\State\Scope $scope) use ($userId, $username): void {
                $scope->setUser(['id' => $userId, 'username' => $username]);
            });
        }

        Sentry\captureException($exception);
    }
}
