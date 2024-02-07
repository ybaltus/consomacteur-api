<?php

namespace App\Command;

use App\Services\OpenDataService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'import:open-data-csv',
    description: 'Import energy consumption csv from OpenData Réseaux-Energies',
)]
class ImportOpenDataCommand extends Command
{
    public function __construct(
        private readonly OpenDataService $openDataService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'The filename')
            ->addArgument('maxDatas', InputArgument::OPTIONAL, 'Define maximum number of data items for processing functions')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        $maxDatas = $input->getArgument('maxDatas') ?? 1000;

        if ($filename) {
            $io->note(sprintf('Filename : %s', $filename));

            $fileExist = $this->openDataService->checkFileExists($filename);

            if (!$fileExist) {
                $io->warning(sprintf('The file %s does not exist.', $filename));

                return Command::FAILURE;
            }

            // Data insertion with Load Data Infile
            $io->title('Start data insertion with Load Data Infile SQL function');
            $startTime = microtime(true);
            $this->openDataService->insertDatasFromCsvFile($filename);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->info('Time execution of insertion data with load data infile : '.number_format($timeExecution, 4).' seconds');

            // Processing time with DQL
            $io->title('Start processing time with DQL : '.$maxDatas.' entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithDQL($maxDatas);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->info('Processing time with DQL queries: '.number_format($timeExecution, 4).' seconds');

            // Processing time with SQL
            $io->title('Start processing time with SQL : '.$maxDatas.' entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithSQL();
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->info('Processing time with SQL queries: '.number_format($timeExecution, 4).' seconds');
        }

        $io->success('Successful data import !');

        return Command::SUCCESS;
    }
}
