<?php

namespace Codememory\Components\Configuration\Modes;

use Codememory\Components\Configuration\ConfigBuilder;
use Codememory\Components\Configuration\Exceptions\ModeMethodIsNotOverriddenException;
use Codememory\Components\Configuration\Interfaces\ConfigBuilderInterface;
use Codememory\Components\Configuration\Interfaces\ModeInterface;
use Codememory\Components\Configuration\Utils;
use JetBrains\PhpStorm\Pure;

/**
 * Class AbstractMode
 *
 * @package Codememory\Components\Configuration\Modes
 *
 * @author  Codememory
 */
abstract class AbstractMode implements ModeInterface
{

    /**
     * @var Utils
     */
    protected Utils $utils;

    /**
     * @var ConfigBuilderInterface
     */
    private ConfigBuilderInterface $configBuilder;

    /**
     * @param Utils $utils
     */
    #[Pure]
    public function __construct(Utils $utils)
    {

        $this->utils = $utils;
        $this->configBuilder = new ConfigBuilder($this->utils, $this);

    }

    /**
     * @inheritDoc
     * @throws ModeMethodIsNotOverriddenException
     */
    public function getModeName(): string
    {

        throw new ModeMethodIsNotOverriddenException('getModeName', static::class);

    }

    /**
     * @inheritDoc
     * @throws ModeMethodIsNotOverriddenException
     */
    public function getSubdirectory(): string
    {

        throw new ModeMethodIsNotOverriddenException('getSubdirectory', static::class);

    }

    /**
     * @inheritDoc
     */
    public function getModeSubdirectories(): array
    {

        return [];

    }

    /**
     * @inheritDoc
     */
    public function getConfigBuilder(): ConfigBuilderInterface
    {

        return $this->configBuilder;

    }

    /**
     * @inheritDoc
     */
    public function getConfigsWithData(): array
    {

        return [];

    }

    /**
     * @inheritDoc
     */
    public function getAllBinds(): array
    {

        return [];

    }

}