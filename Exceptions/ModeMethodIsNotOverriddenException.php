<?php

namespace Codememory\Components\Configuration\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ModeMethodIsNotOverriddenException
 *
 * @package Codememory\Components\Configuration\Exceptions
 *
 * @author  Codememory
 */
class ModeMethodIsNotOverriddenException extends LogicModeException
{

    /**
     * @param string $method
     * @param string $class
     */
    #[Pure]
    public function __construct(string $method, string $class)
    {

        parent::__construct(sprintf('The %s method is not overridden in the %s mode class', $method, $class));

    }

}