<?php

namespace Codememory\Components\Configuration\Modes;

use Codememory\Components\Configuration\ConfigDataFilter;
use Codememory\Components\Markup\Types\YamlType;

/**
 * Class DevelopmentMode
 *
 * @package Codememory\Components\Configuration\Modes
 *
 * @author  Codememory
 */
class DevelopmentMode extends AbstractMode
{

    public const MODE = 'development';

    /**
     * @inheritDoc
     */
    public function getModeName(): string
    {

        return self::MODE;

    }

    /**
     * @inheritDoc
     */
    public function getSubdirectory(): string
    {

        return '/';

    }

    /**
     * @inheritDoc
     */
    public function getModeSubdirectories(): array
    {

        return [
            'basic', 'packages'
        ];

    }

    /**
     * @inheritDoc
     */
    public function getConfigsWithData(): array
    {

        $data = $this->getConfigBuilder()->getMergedConfigs(new YamlType());
        $filter = new ConfigDataFilter($data);

        $filter->addFilter(function (mixed &$value) {
            preg_match_all('/(?<bind_all>%(?<bind>[a-zA-Z0-9._-]+)%)/', $value, $match);

            foreach ($match['bind_all'] as $index => $bind) {
                $value = str_replace($bind, $this->getAllBinds()[$match['bind'][$index]] ?? null, $value);
            }
        })->addFilter(function (mixed &$value) {
            preg_match_all('/(?<full>%(?<func_name>[a-zA-z0-9_]+)\((?<args>[^)]*)\)%)/', $value, $match);

            foreach ($match['full'] as $index => $func) {
                $functionName = $match['func_name'][$index];
                $arguments = $this->explode(',', $match['args'][$index]);
                $resultFunc = null;

                if(function_exists($functionName)) {
                    $resultFunc = call_user_func_array($functionName, $arguments);
                }

                $value = str_replace($func, $resultFunc, $value);
            }
        });

        return $filter->getFormattedConfigs();

    }

    /**
     * @inheritDoc
     */
    public function getAllBinds(): array
    {

        $configs = $this->getConfigBuilder()->getMergedConfigs(new YamlType());

        return $this->getConfigBuilder()->getBinds($configs);

    }

    /**
     * @param string      $separator
     * @param string|null $str
     *
     * @return array
     */
    private function explode(string $separator, ?string $str = null): array
    {

        return empty($str) ? [] : explode($separator, $str);

    }

}