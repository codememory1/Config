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
use Codememory\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AllBindsCommand
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
class AllBindsCommand extends Command
{

    /**
     * @var string|null
     */
    protected ?string $command = 'config:binds';

    /**
     * @var string|null
     */
    protected ?string $description = 'Get a list of all binds from the configuration';

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
        $bindsOutput = [];
        $diffDevAndProd = array_diff($config->getBindsInDevelopmentMode(), $config->getBindsInProductionMode());

        $this->io->title('Configuration bind list');

        if ([] !== $diffDevAndProd) {
            $this->io->warning(sprintf(
                'There are no %s binds in the configuration bind cache',
                count($diffDevAndProd)
            ));
        }

        foreach ($config->getBindsInDevelopmentMode() as $name => $bind) {
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

        return Command::SUCCESS;

    }

}