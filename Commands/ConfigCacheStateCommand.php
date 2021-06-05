<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Caching\Cache;
use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Caching\Utils;
use Codememory\Components\Configuration\Config;
use Codememory\Components\Console\Command;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\IncorrectPathToEnviException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\Components\Markup\Types\YamlType;
use Codememory\FileSystem\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigCacheStateCommand
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class ConfigCacheStateCommand extends StateAbstract
{

    /**
     * @var string|null
     */
    protected ?string $command = 'cache:state:config';

    /**
     * @var string|null
     */
    protected ?string $description = 'Get the state of the configuration cache';

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
        $config = new Config($filesystem);
        $cache = new Cache(new YamlType(), $filesystem);

        $devBinds = serialize($config->getDevelopmentDataWithParsing());
        $prodBinds = serialize($cache->get(Config::TYPE_CACHE, Config::NAME_CACHE));

        $this->getProgress($output, $devBinds, $prodBinds);

        return Command::SUCCESS;

    }

}
