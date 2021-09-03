<?php

namespace Codememory\Components\Configuration\Exceptions;

use ErrorException;
use JetBrains\PhpStorm\Pure;

/**
 * Class AbstractConfigException
 *
 * @package Codememory\Components\Configuration\Exceptions
 *
 * @author  Codememory
 */
abstract class AbstractConfigException extends ErrorException
{

    /**
     * @param string|null $message
     */
    #[Pure]
    public function __construct(?string $message = null)
    {

        parent::__construct($message ?: '');

    }

}