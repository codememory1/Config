<?php

namespace Codememory\Components\Configuration\Interfaces;

/**
 * Interface ConfigInterface
 * @package Codememory\Components\Configuration\Interfaces
 *
 * @author  Codememory
 */
interface ConfigInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Open the configuration to get data from it, using the 3nd
     * argument, control the exception in the absence of a config
     * is indicated
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $config
     * @param array  $default
     * @param bool   $throw
     *
     * @return ConfigInterface
     */
    public function open(string $config, array $default = [], bool $throw = true): ConfigInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Retrieving data from an open configuration, by default,
     * if the $ keys argument is missing, the entire open
     * configuration will be returned
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string|null $keys
     *
     * @return mixed
     */
    public function get(?string $keys = null): mixed;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns a pooled array of the entire configuration
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function all(): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Checks the existence of the config, in case of its absence,
     * the return will be false
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $config
     *
     * @return bool
     */
    public function exist(string $config): bool;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get the value of someone's bind from all configurations
     * or by default will return all binds
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string|null $bind
     *
     * @return array
     */
    public function binds(?string $bind = null): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Add a bind to the list of all binds
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $bind
     * @param mixed  $value
     *
     * @return ConfigInterface
     */
    public function setBind(string $bind, mixed $value): ConfigInterface;

}