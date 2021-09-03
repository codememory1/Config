<?php

namespace Codememory\Components\Configuration;

use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\Configuration\Interfaces\ModeInterface;
use Codememory\Support\Arr;
use JetBrains\PhpStorm\Pure;

/**
 * Class Config
 *
 * @package Codememory\Components\Configuration
 *
 * @author  Codememory
 */
class Config implements ConfigInterface
{

    /**
     * @var string
     */
    private string $configName;

    /**
     * @var array
     */
    private array $defaultData;

    /**
     * @var ModeInterface
     */
    private ModeInterface $mode;

    /**
     * @var array
     */
    private array $configDataFromFile;

    /**
     * @param string        $configName
     * @param array         $defaultData
     * @param ModeInterface $mode
     */
    public function __construct(string $configName, array $defaultData, ModeInterface $mode)
    {

        $this->configName = $configName;
        $this->defaultData = $defaultData;
        $this->mode = $mode;
        $this->configDataFromFile = $this->mode->getConfigsWithData()[$this->configName] ?? [];

    }

    /**
     * @inheritDoc
     */
    public function get(string $keys): mixed
    {

        if (!Arr::exists($this->configDataFromFile, $keys)) {
            return Arr::set($this->defaultData)::get($keys);
        }

        return Arr::set($this->configDataFromFile)::get($keys);

    }

    /**
     * @inheritDoc
     */
    public function all(bool $defaultWhenEmpty = true): array
    {

        if ($defaultWhenEmpty && [] === $this->configDataFromFile) {
            return $this->defaultData;
        }

        return $this->configDataFromFile;

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getBinds(): array
    {

        return $this->all(false)[ConfigBuilder::KEY_WITH_BINDS] ?? [];

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getBind(string $name): mixed
    {

        $binds = $this->getBinds();

        return !array_key_exists($name, $binds) ? null : $binds[$name];

    }

}