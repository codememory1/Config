<?php

namespace Codememory\Components\Configuration\Parsers;

use Codememory\Support\ConvertType;
use Codememory\Support\Str;

/**
 * Class ParsingInValues
 * @package Codememory\Components\Configuration\Parsers
 *
 * @author  Codememory
 */
class ParsingInValues
{

    /**
     * @var array
     */
    private array $configData;

    /**
     * @var array
     */
    private array $binds;

    /**
     * @var array|string[]
     */
    private array $functions = [
        'env'
    ];

    /**
     * ParsingInValues constructor.
     *
     * @param array $data
     * @param array $binds
     */
    public function __construct(array $data, array $binds)
    {

        $this->configData = $data;
        $this->binds = $binds;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of all the configuration that was parsed
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getConfigData(): array
    {

        $this->parser($this->configData);

        return $this->configData;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Searches for binds in the configuration values, if any,
     * replaces them with the values from the bind
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param mixed $data
     *
     * @return void
     */
    private function findBindsToValues(mixed &$data): void
    {

        if (preg_match_all('/%(?<binds>[a-z.-_]+)%/i', $data, $match)) {
            foreach ($match['binds'] as $bind) {
                Str::replace($data, "%$bind%", $this->binds[$bind] ?? '');
            }

        }

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Search for functions in configuration values, if any function
     * is found and it is allowed to be used, it will be replaced
     * with the value that this function returns
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param mixed $data
     *
     * @return ParsingInValues
     */
    private function findFuncToValues(mixed &$data): ParsingInValues
    {

        foreach ($this->functions as $function) {
            $regex = "/%$function\((?<arguments>[^()]*)\)%/i";

            if (preg_match_all($regex, $data, $match)) {
                foreach ($match['arguments'] as $argument) {
                    $arguments = explode(',', $argument);

                    array_map(fn (mixed $value) => trim($value), $arguments);

                    Str::replace($data, "%$function($argument)%", $function(...$arguments) ?: '');
                }
            }
        }

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * The main method in which the loop goes through
     * each config and finds
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $configData
     *
     * @return void
     */
    private function parser(array &$configData): void
    {

        $converterType = new ConvertType();

        foreach ($configData as &$data) {
            if (is_array($data)) {
                $this->parser($data);
            } else {
                $this
                    ->findFuncToValues($data)
                    ->findBindsToValues($data);

                $data = $converterType->auto($data);
            }
        }

    }

}
