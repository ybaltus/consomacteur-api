<?php

namespace App\Command;

use App\Services\InitDataService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'init:datas-db',
    description: 'Initialize datas for the database',
)]
class InitDataDBCommand extends Command
{
    public function __construct(
        private readonly InitDataService $initDataService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // EnergyTypes
        $this->initDataService->initEnergyTypes();
        $io->info('InitEnergyTypes OK');

        $io->success('Successful data initialization !');

        return Command::SUCCESS;
    }
}
