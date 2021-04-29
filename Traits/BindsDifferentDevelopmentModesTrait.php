<?php

namespace Codememory\Components\Configuration\Traits;

use Codememory\Components\Caching\Cache;
use Codememory\Components\Markup\Types\YamlType;

/**
 * Trait BindsDifferentDevelopmentModesTrait
 * @package Codememory\Components\Configuration\Traits
 *
 * @author  Codememory
 */
trait BindsDifferentDevelopmentModesTrait
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * A handler that returns an array of bindings in
     * development mode
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    private function bindsInDevelopmentMode(): array
    {

        return $this->fetchBinds($this->developmentMode());

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * This method is identical to the bindsInDevelopmentMode method
     * with the other difference, it returns data from
     * the cache(in production mode)
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return mixed
     */
    private function bindsInProductionMode(): mixed
    {

        $cache = new Cache(new YamlType(), $this->filesystem);

        return $cache->get(self::TYPE_CACHE, self::KEY_WITH_BINDS);

    }

}