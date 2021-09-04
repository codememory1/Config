<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Configuration\Configuration;
use Codememory\Components\Configuration\Modes\ProductionMode;
use Codememory\Components\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BindCacheStateCommand
 *
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class BindCacheStateCommand extends AbstractState
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
     * @inheritDoc
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $devConfigs = serialize(Configuration::getInstance()->getBinds());
        $prodConfigs = serialize(Configuration::getInstance()->getModeHandler(ProductionMode::MODE)->getAllBinds());

        $this->getProgress($output, $devConfigs, $prodConfigs);

        return Command::SUCCESS;

    }

}