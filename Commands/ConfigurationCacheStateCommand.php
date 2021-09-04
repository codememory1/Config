<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Configuration\Configuration;
use Codememory\Components\Configuration\Modes\ProductionMode;
use Codememory\Components\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigurationCacheStateCommand
 *
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class ConfigurationCacheStateCommand extends AbstractState
{

    /**
     * @inheritDoc
     */
    protected ?string $command = 'cache:state:config';

    /**
     * @inheritDoc
     */
    protected ?string $description = 'Get the state of the configuration cache';

    /**
     * @inheritDoc
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $devConfigs = serialize(Configuration::getInstance()->getAllConfigs());

        $prodConfigs = serialize(Configuration::getInstance()->getModeHandler(ProductionMode::MODE)->getConfigsWithData());

        $this->getProgress($output, $devConfigs, $prodConfigs);

        return self::SUCCESS;

    }

}