<?php

namespace App\Errors;

use Cake\Core\Exception\Exception;

/**
 * Default Usage Error Handler
 */
class UsageError extends Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
        if (empty($message)) {
            $message = 'Unexpected Usage Error';
        }
        parent::__construct($message, $code, $previous);
    }
}
