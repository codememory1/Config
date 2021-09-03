<?php

namespace Codememory\Components\Configuration\Exceptions;

use Codememory\Components\Configuration\Interfaces\ModeInterface;
use JetBrains\PhpStorm\Pure;

/**
 * Class ModeNotImplementInterfaceException
 *
 * @package Codememory\Components\Configuration\Exceptions
 *
 * @author  Codememory
 */
class ModeNotImplementInterfaceException extends AbstractModeException
{

    /**
     * @param string $class
     */
    #[Pure]
    public function __construct(string $class)
    {

        parent::__construct(sprintf(
            'The %s class does not implement the %s interface and cannot be a mode',
            $class,
            ModeInterface::class
        ));

    }

}