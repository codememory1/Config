<?php

namespace Codememory\Components\Configuration\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ConfigNotFoundException
 * @package Codememory\Components\Configuration\Exceptions
 *
 * @author  Codememory
 */
class ConfigNotFoundException extends ConfigurationException
{

    /**
     * ConfigNotFoundException constructor.
     *
     * @param string $config
     */
    #[Pure] public function __construct(string $config)
    {

        parent::__construct(sprintf(
            'Configuration %s could not be opened. Due to her absence',
            $config
        ));

    }

}