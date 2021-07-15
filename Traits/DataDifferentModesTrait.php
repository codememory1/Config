<?php

namespace Codememory\Components\Configuration\Traits;

use Codememory\Components\Finder\Find;
use Codememory\Components\Markup\Markup;
use Codememory\Components\Markup\Types\YamlType;
use Codememory\Support\Arr;
use Codememory\Support\Str;

/**
 * Trait DataDifferentModesTrait
 *
 * @package Codememory\Components\Configuration\Traits
 *
 * @author  Codememory
 */
trait DataDifferentModesTrait
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of bindings from the data
     * passed as an argument
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $dataConfigs
     *
     * @return array
     */
    private function fetchBinds(array $dataConfigs): array
    {

        $binds = [];

        foreach ($dataConfigs as $dataConfig) {
            if (is_array($dataConfig) && Arr::exists($dataConfig, self::KEY_WITH_BINDS)) {
                $binds = array_merge($binds, $dataConfig[self::KEY_WITH_BINDS]);
            }
        }

        return $binds;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * It searches for all configuration files in a specific folder,
     * then all found files are combined into one array. Using the
     * argument, you can remove all binds in the configuration
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param bool $removeBinds
     *
     * @return array
     */
    private function developmentMode(bool $removeBinds = false): array
    {

        $finder = new Find();
        $markup = new Markup(new YamlType());

        $data = [];
        $configs = $finder
            ->setPathForFind($this->configPath)
            ->file()
            ->byRegex('\/[a-zA-Z-_]+.yaml$')
            ->get();

        foreach ($configs as $config) {
            $config = Str::cut($config, mb_stripos($config, '.'));
            $dataConfig = $markup->open($config)->get();

            if ($removeBinds) {
                $dataConfig = $this->removeBindsFromConfig($dataConfig);
            }

            $data = array_merge($data, $dataConfig);
        }

        return $data;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Processing that removes all binds and configurations
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $dataConfig
     *
     * @return array
     */
    private function removeBindsFromConfig(array $dataConfig): array
    {

        foreach ($dataConfig as $configName => $data) {
            if (is_array($data) && Arr::exists($data, self::KEY_WITH_BINDS)) {
                unset($dataConfig[$configName][self::KEY_WITH_BINDS]);
            }
        }

        return $dataConfig;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the configuration from the cache, if the cache does
     * not exist it will return an empty array
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    private function productionMode(): array
    {

        return $this->cache->get(self::TYPE_CACHE, self::NAME_CACHE);

    }

}
