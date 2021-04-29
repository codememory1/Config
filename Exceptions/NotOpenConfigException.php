<?php

namespace Codememory\Components\Configuration\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class NotOpenConfigException
 * @package Codememory\Components\Configuration\Exceptions
 *
 * @author  Codememory
 */
class NotOpenConfigException extends ConfigurationException
{

    /**
     * NotOpenConfigException constructor.
     */
    #[Pure] public function __construct()
    {

        parent::__construct('To get data from the configuration, first you need to open the configuration using the open method');

    }

}