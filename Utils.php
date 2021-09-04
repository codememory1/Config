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
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the path where all subdirectories of modes and their files are located
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return string
     */
    public function getPathWithConfigs(): string
    {

        return GlobalConfig::get('configuration.pathWithConfigs') ?: '/';

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the name of the active mode, if the mode is null then the default
     * will be "DevelopmentMode"
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return string
     */
    public function getMode(): string
    {

        return GlobalConfig::get('configuration.mode') ?: DevelopmentMode::MODE;

    }

}