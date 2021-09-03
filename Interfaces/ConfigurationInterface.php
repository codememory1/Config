<?php

namespace Codememory\Components\Configuration\Interfaces;

/**
 * Interface ConfigurationInterface
 *
 * @package Codememory\Components\Configuration\Interfaces
 *
 * @author  Codememory
 */
interface ConfigurationInterface
{

    /**
     * @param string $modeNamespace
     *
     * @return ConfigurationInterface
     */
    public function addModeHandler(string $modeNamespace): ConfigurationInterface;

    /**
     * @return string
     */
    public function getModeName(): string;

    /**
     * @return ModeInterface
     */
    public function getModeHandler(): ModeInterface;

    /**
     * @param string $configName
     * @param array  $defaultData
     *
     * @return ConfigInterface
     */
    public function open(string $configName, array $defaultData = []): ConfigInterface;

    /**
     * @return array
     */
    public function getAllConfigs(): array;

    /**
     * @return array
     */
    public function getBinds(): array;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getBind(string $name): mixed;

}