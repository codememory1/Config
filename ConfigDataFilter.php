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
     * @return array
     */
    public function getFormattedConfigs(): array
    {

        $this->recursiveReplacement($this->mergedConfigs);

        return $this->mergedConfigs;

    }

    /**
     * @param array $data
     */
    private function recursiveReplacement(array &$data): void
    {

        foreach ($this->iteration($data) as &$value) {
            if (is_array($value)) {
                $this->recursiveReplacement($value);
            } else {
                foreach ($this->filters as $filter) {
                    call_user_func_array($filter, [&$value]);
                }
            }
        }

    }

    /**
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