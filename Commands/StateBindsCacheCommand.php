<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Configuration\Config;
use Codememory\Components\Console\Command;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\IncorrectPathToEnviException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\FileSystem\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StateBindsCacheCommand
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class StateBindsCacheCommand extends StateAbstract
{

    /**
     * @var string|null
     */
    protected ?string $command = 'cache:state:binds';

    /**
     * @var string|null
     */
    protected ?string $description = 'Get the state of the configuration bind cache';

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

        $this->getProgress($output, serialize($config->getBindsInDevelopmentMode()), serialize($config->getBindsInProductionMode()));

        return Command::SUCCESS;

    }

}