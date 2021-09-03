<?php

namespace Codememory\Components\Configuration;

use Codememory\Components\Configuration\Modes\DevelopmentMode;
use Codememory\Components\GlobalConfig\GlobalConfig;

/**
 * Class Utils
 *
 * @package Codememory\Components\Configuration
 *
 * @author  Codememory
 */
class Utils
{

    /**
     * @return string
     */
    public function getPathWithConfigs(): string
    {

        return GlobalConfig::get('configuration.pathWithConfigs') ?: '/';

    }

    /**
     * @return string
     */
    public function getMode(): string
    {

        return GlobalConfig::get('configuration.mode') ?: DevelopmentMode::MODE;

    }

}