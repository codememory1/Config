<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Configuration\Configuration;
use Codememory\Components\Console\Command;
use Codememory\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AllBindsCommand
 *
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class AllBindsCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected ?string $command = 'cache:binds';

    /**
     * @inheritDoc
     */
    protected ?string $description = 'Get a list of all binds from the configuration';

    /**
     * @inheritDoc
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $bindsOutput = [];
        $binds = Configuration::getInstance()->getBinds();

        $this->io->title('Configuration bind list');

        foreach ($binds as $name => $bind) {
            $bindName = $this->tags->whiteText("Bind name: %s\n ");
            $bindName = sprintf($bindName, $this->tags->greenText($name));
            $bindValue = $this->tags->whiteText("Value: %s\n");
            $bindValue = sprintf($bindValue, $this->tags->greenText($bind));
            $lengthName = Str::length($bindName);
            $lengthValue = Str::length($bindValue);

            $length = $lengthName > $lengthValue ? $lengthName : $lengthValue;

            $bindsOutput[] = sprintf("%s%s %s\n", $bindName, $bindValue, Str::repeat('-', $length / 2));
        }

        $this->io->text($bindsOutput);

        return self::SUCCESS;

    }

}