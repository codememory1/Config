<?php

namespace Codememory\Components\Configuration\Interfaces;

/**
 * Interface ModeInterface
 *
 * @package Codememory\Components\Configuration\Interfaces
 *
 * @author  Codememory
 */
interface ModeInterface
{

    /**
     * @return string
     */
    public function getModeName(): string;

    /**
     * @return string
     */
    public function getSubdirectory(): string;

    /**
     * @return array
     */
    public function getModeSubdirectories(): array;

    /**
     * @return ConfigBuilderInterface
     */
    public function getConfigBuilder(): ConfigBuilderInterface;

    /**
     * @return array
     */
    public function getConfigsWithData(): array;

    /**
     * @return array
     */
    public function getAllBinds(): array;

}