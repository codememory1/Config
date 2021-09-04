<?php

namespace Codememory\Components\Configuration\Interfaces;

use Codememory\Components\Markup\Interfaces\MarkupTypeInterface;

/**
 * Interface ConfigBuilderInterface
 *
 * @package Codememory\Components\Configuration\Interfaces
 *
 * @author  Codememory
 */
interface ConfigBuilderInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of the entire configuration
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param MarkupTypeInterface $markupType
     *
     * @return array
     */
    public function getMergedConfigs(MarkupTypeInterface $markupType): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns all binds from an array of the entire configuration
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $mergedConfigs
     *
     * @return array
     */
    public function getBinds(array $mergedConfigs): array;

}