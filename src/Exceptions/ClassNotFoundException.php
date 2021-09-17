<?php

namespace EiaComposer\Payment\Exceptions;

/**
 * Class ClassNotFoundException
 * @package EiaComposer\Payment\Exceptions
 */
class ClassNotFoundException extends \RuntimeException
{

    /**
     * GatewayErrorException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }

}
