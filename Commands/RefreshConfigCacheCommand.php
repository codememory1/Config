<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Caching\Cache;
use Codememory\Components\Configuration\Configuration;
use Codememory\Components\Configuration\Modes\ProductionMode;
use Codememory\Components\Console\Command;
use Codememory\Components\Markup\Types\YamlType;
use Codememory\FileSystem\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RefreshConfigCacheCommand
 *
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class RefreshConfigCacheCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected ?string $command = 'cache:update:config';

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $filesystem = new File();
        $cache = new Cache(new YamlType(), $filesystem);

        if ($input->getOption('all') || !$input->getOption('update-binds')) {
            $cache->create(ProductionMode::TYPE_CACHE, ProductionMode::NAME_CACHE, Configuration::getInstance()->getAllConfigs());

            $this->io->success('Config cache updated');
        }

        if ($input->getOption('update-binds') || $input->getOption('all')) {
            $cache->create(ProductionMode::TYPE_CACHE, ProductionMode::BINDS_CACHE_NAME, Configuration::getInstance()->getBinds());

            $this->io->success('Config bind cache updated');
        }

        return Command::SUCCESS;

    }

}