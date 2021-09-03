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
     * @param string $keys
     *
     * @return mixed
     */
    public function get(string $keys): mixed;

    /**
     * @param bool $defaultWhenEmpty
     *
     * @return array
     */
    public function all(bool $defaultWhenEmpty = true): array;

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