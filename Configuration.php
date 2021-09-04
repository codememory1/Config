<?php

namespace Codememory\Components\Configuration;

use Codememory\Components\Configuration\Exceptions\ModeNotImplementInterfaceException;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\Configuration\Interfaces\ConfigurationInterface;
use Codememory\Components\Configuration\Interfaces\ModeInterface;
use Codememory\Components\Configuration\Modes\DevelopmentMode;
use Codememory\Components\Configuration\Modes\ProductionMode;
use Codememory\Patterns\Singleton\SingletonTrait;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * Class Configuration
 *
 * @package Codememory\Components\Configuration
 *
 * @author  Codememory
 */
class Configuration implements ConfigurationInterface
{

    use SingletonTrait;

    /**
     * @var Utils
     */
    private Utils $utils;

    /**
     * @var array
     */
    private array $modeHandlers = [];

    /**
     * @throws ModeNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function __construct()
    {

        $this->utils = new Utils();

        $this->addModeHandler(DevelopmentMode::class);
        $this->addModeHandler(ProductionMode::class);

    }

    /**
     * @inheritDoc
     * @throws ModeNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function addModeHandler(string $modeNamespace): ConfigurationInterface
    {

        if (!class_exists($modeNamespace)) {
            throw new RuntimeException(sprintf('The class %s not found', $modeNamespace));
        }

        $reflector = new ReflectionClass($modeNamespace);

        if (!$reflector->implementsInterface(ModeInterface::class)) {
            throw new ModeNotImplementInterfaceException($modeNamespace);
        }

        $mode = $reflector->newInstance($this->utils);

        $this->modeHandlers[$mode->getModeName()] = $mode;

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function getModeName(): string
    {

        if (!array_key_exists($this->utils->getMode(), $this->modeHandlers)) {
            return DevelopmentMode::MODE;
        }

        return $this->utils->getMode();

    }

    /**
     * @inheritDoc
     */
    public function getModeHandler(?string $modeName = null): ModeInterface
    {

        return $this->modeHandlers[$modeName ?: $this->getModeName()];

    }

    /**
     * @inheritDoc
     */
    public function open(string $configName, array $defaultData = []): ConfigInterface
    {

        return new Config($configName, $defaultData, $this->getModeHandler());

    }

    /**
     * @inheritDoc
     */
    public function getAllConfigs(): array
    {

        return $this->getModeHandler()->getConfigsWithData();

    }

    /**
     * @inheritDoc
     */
    public function getBinds(): array
    {

        return $this->getModeHandler()->getAllBinds();

    }

    /**
     * @inheritDoc
     */
    public function getBind(string $name): mixed
    {

        return !array_key_exists($name, $this->getBinds()) ? null : $this->getBinds()[$name];

    }

}