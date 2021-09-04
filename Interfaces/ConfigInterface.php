<?php

namespace Codememory\Components\Configuration\Interfaces;

/**
 * Interface ConfigInterface
 *
 * @package Codememory\Components\Configuration\Interfaces
 *
 * @author  Codememory
 */
interface ConfigInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get value from open configuration by key
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $keys
     *
     * @return mixed
     */
    public function get(string $keys): mixed;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of open configuration data
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param bool $defaultWhenEmpty
     *
     * @return array
     */
    public function all(bool $defaultWhenEmpty = true): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns all binds of the open configuration
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getBinds(): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get the bind value from the rooted configuration
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getBind(string $name): mixed;

}