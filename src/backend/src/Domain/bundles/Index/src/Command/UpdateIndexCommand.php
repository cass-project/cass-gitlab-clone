<?php
namespace Domain\Index;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UpdateIndexCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('index:update')
            ->setDescription('Reindexate all entities [SLOW] [REALLY SLOW]');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}