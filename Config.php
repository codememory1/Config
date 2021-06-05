<?php

namespace Codememory\Components\Configuration;

use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Configuration\Exceptions\NotOpenConfigException;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\Configuration\Parsers\ParsingInValues;
use Codememory\Components\Configuration\Traits\BindsDifferentDevelopmentModesTrait;
use Codememory\Components\Configuration\Traits\DataDifferentModesTrait;
use Codememory\Components\Environment\Environment;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\IncorrectPathToEnviException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\Components\GlobalConfig\GlobalConfig;
use Codememory\FileSystem\Interfaces\FileInterface;
use Codememory\Support\Arr;

/**
 * Class Config
 * @package Codememory\Components\Configuration
 *
 * @author  Codememory
 */
class Config implements ConfigInterface
{

    use DataDifferentModesTrait;
    use BindsDifferentDevelopmentModesTrait;

    public const TYPE_CACHE = 'configs';
    public const NAME_CACHE = 'all_configs';
    public const KEY_WITH_BINDS = 'binds';

    /**
     * @var FileInterface
     */
    private FileInterface $filesystem;

    /**
     * @var string
     */
    private string $configPath;

    /**
     * @var array
     */
    private array $binds;

    /**
     * @var array|array[]
     */
    private array $configData;

    /**
     * @var string|null
     */
    private ?string $openedConfig = null;

    /**
     * @var array
     */
    private array $defaultDataOpenedConfig = [];

    /**
     * @var bool
     */
    private bool $withCache;

    /**
     * @var bool
     */
    private bool $isProd = false;

    /**
     * Config constructor.
     *
     * @param FileInterface $filesystem
     *
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectPathToEnviException
     * @throws ParsingErrorException
     * @throws VariableParsingErrorException
     */
    public function __construct(FileInterface $filesystem)
    {

        $this->filesystem = $filesystem;
        $this->configPath = GlobalConfig::get('configuration.pathWithConfigs');
        $this->withCache = GlobalConfig::get('configuration.useCache');

        $prodBinds = [];
        $prodData = [];

        if ($this->withCache) {
            Environment::__constructStatic($this->filesystem);

            $prodBinds = $this->bindsInProductionMode();
            $prodData = $this->productionMode();
            $this->isProd = isProd();
        }

        $this->binds = [
            'dev'  => $this->bindsInDevelopmentMode(),
            'prod' => $prodBinds
        ];
        $this->configData = [
            'dev'  => $this->getDevelopmentDataWithParsing(),
            'prod' => $prodData
        ];
        $this->configData['auto'] = $this->isProd ? $this->configData['prod'] : $this->configData['dev'];

    }

    /**
     * {@inheritdoc}
     *
     * @throws ConfigNotFoundException
     */
    public function open(string $config, array $default = [], bool $throw = true): ConfigInterface
    {

        $this->openedConfig = $config;
        $this->defaultDataOpenedConfig = $default;

        if ($throw && [] === $default && !$this->exist($this->openedConfig)) {
            throw new ConfigNotFoundException($config);
        }

        return $this;

    }

    /**
     * {@inheritdoc}
     *
     * @throws NotOpenConfigException
     */
    public function get(?string $keys = null): mixed
    {

        if (null === $this->openedConfig) {
            throw new NotOpenConfigException();
        }

        $configData = $this->configData['auto'][$this->openedConfig] ?? $this->defaultDataOpenedConfig;

        if (null === $keys) {
            return $configData;
        }

        return Arr::set($configData)::get($keys);

    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {

        return $this->configData['auto'];

    }

    /**
     * @param string $config
     *
     * @return bool
     */
    public function exist(string $config): bool
    {

        return Arr::exists($this->configData['auto'], $config);

    }

    /**
     * @inheritDoc
     */
    public function binds(?string $bind = null): array
    {

        $binds = $this->isProd ? $this->binds['prod'] : $this->binds['dev'];

        if (null === $bind) {
            return $binds;
        }

        return $binds[$bind];

    }

    /**
     * @inheritDoc
     */
    public function setBind(string $bind, mixed $value): ConfigInterface
    {

        if ($this->isProd) {
            $this->binds['prod'][$bind] = $value;
        } else {
            $this->binds['dev'][$bind] = $value;
        }

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns a pooled array of the entire configuration
     * from design mode
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getDevelopmentDataWithParsing(): array
    {

        $parserValues = new ParsingInValues($this->developmentMode(true), $this->binds());

        return $parserValues->getConfigData();

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of bindings from design mode
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getBindsInDevelopmentMode(): array
    {

        return $this->binds['dev'];

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of bindings from production mode
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getBindsInProductionMode(): array
    {

        return $this->binds['prod'];

    }

  
