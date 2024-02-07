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
            Source: https://odre.opendatasoft.com/explore/dataset/eco2mix-regional-cons-def/export/?disjunctive.libelle_region&disjunctive.nature
            Date: 2024/02/02
            Size : 291Mo
            Nb entries : > 1M
            ', $filename));

            $fileExist = $this->openDataService->checkFileExists($filename);

            if (!$fileExist) {
                $io->warning(sprintf('The file %s does not exist.', $filename));

                return Command::FAILURE;
            }

            // Data insertion with Load Data Infile in 1 table
            $io->title('Data insertion with Load Data Infile SQL function : All entries in a single table');
            $startTime = microtime(true);
            $this->openDataService->insertDatasFromCsvFile($filename);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Data insertion with Load Data Infile in a table by energy
            $io->title('Data insertion with Load Data Infile SQL function : All entries in a table by energy');
            $startTime = microtime(true);
            $this->openDataService->insertDatasFromCsvFile($filename, true);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Processing time with DQL
            $io->title('Processing time with DQL after loadDataInfile : '.$maxDatas.' entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithDQL($maxDatas);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Processing time with SQL
            $io->title('Processing time with SQL after loadDataInfile : '.$maxDatas.' entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithSQL($maxDatas);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');

            // Processing time with SQL
            $io->title('Processing time with SQL after loadDataInfile : All entries');
            $startTime = microtime(true);
            $this->openDataService->processingWithSQL(-1);
            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            $io->comment('Time execution : '.number_format($timeExecution, 4).' seconds');
        }

        $io->success('Successful data import !');

        return Command::SUCCESS;
    }
}
