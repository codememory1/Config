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
     * @param MarkupTypeInterface $markupType
     *
     * @return array
     */
    public function getMergedConfigs(MarkupTypeInterface $markupType): array;

    /**
     * @param array $mergedConfigs
     *
     * @return array
     */
    public function getBinds(array $mergedConfigs): array;

}