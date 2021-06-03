<?php

namespace Codememory\Components\Configuration\Commands;

use Codememory\Components\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StateAbstract
 * @package Codememory\Components\Configuration\Commands
 *
 * @author  Codememory
 */
abstract class StateAbstract extends Command
{

    /**
     * @param OutputInterface $output
     * @param string          $toModeDev
     * @param string          $toModeProd
     */
    protected function getProgress(OutputInterface $output, string $toModeDev, string $toModeProd)
    {

        $progressBar = new ProgressBar($output);
        similar_text($toModeDev, $toModeProd, $percent);

        $this->io->newLine();
        $this->io->writeln($this->tags->yellowText(" The indicator shows how much the cache has been updated, \n if the indicator is less than 100 the cache is not yet updated"));
        $this->io->newLine();

        $this->io->writeln(
            $this->tags->redText(sprintf(
                ' Maximum condition accuracy: %s', $this->tags->whiteText($percent . '%')
            ))
        );
        $this->io->newLine();

        $progressBar->setBarCharacter($this->tags->colorText('=', '#b729d9'));
        $progressBar->setEmptyBarCharacter($this->tags->whiteText('-'));
        $progressBar->setProgressCharacter($this->tags->redText('>'));
        $progressBar->start(100);

        $i = 0;
        while (++$i <= $percent) {
            $progressBar->advance();
            $progressBar->display();

            usleep(10000);
        }

        $this->io->newLine(3);

    }

}