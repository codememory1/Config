<?php

namespace Codememory\Components\Configuration\Modes;

use Codememory\Components\Caching\Cache;
use Codememory\Components\Caching\Interfaces\CacheInterface;
use Codememory\Components\Configuration\Utils;
use Codememory\Components\Markup\Types\YamlType;
use Codememory\FileSystem\File;

/**
 * Class ProductionMode
 *
 * @package Codememory\Components\Configuration\Modes
 *
 * @author  Codememory
 */
class ProductionMode extends AbstractMode
{

    public const TYPE_CACHE = '__cdm-configuration';
    public const NAME_CACHE = 'configs';
    public const BINDS_CACHE_NAME = 'binds';
    public const MODE = 'production';

    /**
     * @var CacheInterface
     */
    private CacheInterface $caching;

    /**
     * @inheritDoc
     */
    public function __construct(Utils $utils)
    {

        parent::__construct($utils);

        $this->caching = new Cache(new YamlType(), new File());

    }

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

        return '_prod';

    }

    /**
     * @inheritDoc
     */
    public function getConfigsWithData(): array
    {

        return $this->caching->get(self::TYPE_CACHE, self::NAME_CACHE);

    }

    /**
     * @inheritDoc
     */
    public function getAllBinds(): array
    {

        return $this->caching->get(self::TYPE_CACHE, self::BINDS_CACHE_NAME);

    }

}