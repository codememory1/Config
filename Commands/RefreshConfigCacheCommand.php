<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Caching\Cache;
use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Caching\Interfaces\CacheInterface;
use Codememory\Components\Caching\Utils;
use Codememory\Components\Configuration\Config;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\Console\Command;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\IncorrectPathToEnviException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\Components\JsonParser\Exceptions\JsonErrorException;
use Codememory\Components\JsonParser\JsonParser;
use Codememory\Components\Markup\Types\YamlType;
use Codememory\FileSystem\File;
use Codememory\FileSystem\Interfaces\FileInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RefreshConfigCacheCommand
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class RefreshConfigCacheCommand extends Command
{

    /**
     * @var string|null
     */
    protected ?string $command = 'cache:update:config';

    /**
     * @var string|null
     */
    protected ?string $description = 'Refresh the cache of the entire configuration';

    /**
     * @return Command
     */
    protected function wrapArgsAndOptions(): Command
    {

        $this
            ->option('update-binds', null, InputOption::VALUE_NONE, 'Refresh bind cache from config')
            ->option('all', null, InputOption::VALUE_NONE, 'Update all cache configurations and bind cache');

        return $this;

    }

    /**
     * {@inheritdoc}
     *
     * @throws ConfigPathNotExistException
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectPathToEnviException
     * @throws ParsingErrorException
     * @throws VariableParsingErrorException
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $filesystem = new File();
        $cache = new Cache(new YamlType(), $filesystem);
        $config = new Config($filesystem);
        $json = new JsonParser();

        $configDataToModeDevelopment = $config->getDevelopmentDataWithParsing();

        if ($input->getOption('all') || !$input->getOption('update-binds')) {
            $cache
                ->create(
                    Config::TYPE_CACHE,
                    Config::NAME_CACHE,
                    $configDataToModeDevelopment,
                    function (FileInterface $filesystem, string $path, mixed $data) use ($json) {
                        $this->createJsonFileWithCache($filesystem, $json, $path, $data);
                    });

            $this->io->success('Config cache updated');
        }

        if ($input->getOption('update-binds') || $input->getOption('all')) {
            $this->updateBinds($cache, $config, $json);

            $this->io->success('Config bind cache updated');
        }

        return Command::SUCCESS;

    }

    /**
     * @param FileInterface $filesystem
     * @param JsonParser    $json
     * @param string        $path
     * @param mixed         $data
     *
     * @return void
     * @throws JsonErrorException
     */
    private function createJsonFileWithCache(FileInterface $filesystem, JsonParser $json, string $path, mixed $data): void
    {

        $filesystem->writer
            ->open($path . '.json', 'w', true)
            ->put($json->setData($data)->encode());

        return;

    }

    /**
     * @param CacheInterface  $cache
     * @param ConfigInterface $config
     * @param JsonParser      $json
     *
     * @return RefreshConfigCacheCommand
     */
    private function updateBinds(CacheInterface $cache, ConfigInterface $config, JsonParser $json): RefreshConfigCacheCommand
    {

        $binds = $config->getBindsInDevelopmentMode();

        $cache
            ->create(
                Config::TYPE_CACHE,
                Config::KEY_WITH_BINDS,
                $binds,
                function (FileInterface $filesystem, string $path, mixed $data) use ($json) {
                    $this->createJsonFileWithCache($filesystem, $json, $path, $data);
                });

        return $this;

    }

}
