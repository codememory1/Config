<?php

namespace Codememory\Components\Configuration;

use Generator;

/**
 * Class ConfigDataFilter
 *
 * @package Codememory\Components\Configuration
 *
 * @author  Codememory
 */
class ConfigDataFilter
{

    /**
     * @var array
     */
    private array $mergedConfigs;

    /**
     * @var array
     */
    private array $filters = [];

    /**
     * @param array $mergedConfigs
     */
    public function __construct(array $mergedConfigs)
    {

        $this->mergedConfigs = $mergedConfigs;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Add a new filter handler that will be called when trying to get filtered data from
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param callable $callback
     *
     * @return ConfigDataFilter
     */
    public function addFilter(callable $callback): ConfigDataFilter
    {

        $this->filters[] = $callback;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * We process all added filter handlers and return filtered data
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getFormattedConfigs(): array
    {

        $this->recursiveExecutionHandlers($this->mergedConfigs);

        return $this->mergedConfigs;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Recursive execution of handlers if the value from the configuration is not an array
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $data
     */
    private function recursiveExecutionHandlers(array &$data): void
    {

        foreach ($this->iteration($data) as &$value) {
            if (is_array($value)) {
                $this->recursiveExecutionHandlers($value);
            } else {
                foreach ($this->filters as $filter) {
                    call_user_func_array($filter, [&$value]);
                }
            }
        }

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an iteration generator by reference
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $data
     *
     * @return Generator
     */
    private function &iteration(array &$data): Generator
    {

        foreach ($data as &$value) {
            yield $value;
        }

    }

}