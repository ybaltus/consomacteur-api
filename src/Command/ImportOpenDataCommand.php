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

        // Limit 1k
        if ($maxDatas > 2000) {
            $maxDatas = 2000;
        }

        if ($filename) {
            $io->block(sprintf('
            Filename : %s
            Source: https://odre.opendatasoft.com/explore/dataset/eco2mix-regional-cons-def/export
            Date: 2024/02/02
            Size : 291Mo
            Nb entries : 2M
            ', $filename));

            $fileExist = $this->openDataService->checkFileExists($filename);

            if (!$fileExist) {
                $io->warning(sprintf('The file %s does not exist.', $filename));

                return Command::FAILURE;
            }

            // Insert data with sql command "LOAD DATA INFILE" in 1 table
            $io->title('Insert data with sql command "LOAD DATA INFILE" : All entries in a single table');
            $startTime = microtime(true);
            $this->openDataService->insertDatasFromCsvFileWithLoadDataInfile($filename);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Insert data with sql command "LOAD DATA INFILE" in a table by energy
            $io->title('Insert data with sql command "LOAD DATA INFILE" (x6) : All entries in a table by energy');
            $startTime = microtime(true);
            $this->openDataService->insertDatasFromCsvFileWithLoadDataInfile($filename, true);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Processing time with DQL
            $io->title('Test processing time with DQL after "LOAD DATA INFILE" : '.$maxDatas.' entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithDQLAfterLoadDataInfile($maxDatas);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Processing time with SQL
            $io->title('Test processing time with SQL after "LOAD DATA INFILE" : '.$maxDatas.' entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithSQLAfterLoadDataInfile($maxDatas);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Processing time with SQL
            $io->title('Test processing time with SQL after "LOAD DATA INFILE" : All entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithSQLAfterLoadDataInfile(-1);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');
        }

        $io->success('Successful data import !');

        return Command::SUCCESS;
    }
}
