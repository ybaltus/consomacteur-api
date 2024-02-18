<?php

namespace App\MessageHandler;

use App\Message\ImportOpenDataCsv;
use App\Services\OpenDataService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ImportOpenDataCsvHandler
{
    public function __construct(
        private OpenDataService $openDataService
    ) {
    }

    public function __invoke(ImportOpenDataCsv $message): void
    {
        // Abandoned - Too much memory used
        // $this->openDataService->insertDatasFromCsvFileWithMessenger($message->getFilename());

        // Insert data with sql command "LOAD DATA INFILE" in 1 table
        echo 'Insert data with sql command "LOAD DATA INFILE" : All entries in a single table'.PHP_EOL;
        $startTime = microtime(true);
        $this->openDataService->insertDatasFromCsvFileWithLoadDataInfile($message->getFilename());
        $endTime = microtime(true);
        $timeExecution = $endTime - $startTime;
        echo 'Time execution : '.number_format($timeExecution, 4).' seconds'.PHP_EOL;

        // Processing time with SQL
        echo 'Processing time with SQL after \'LOAD DATA INFILE\'  : All entries'.PHP_EOL;
        $startTime = microtime(true);
        $this->openDataService->processingWithSQLAfterLoadDataInfile(-1);
        $endTime = microtime(true);
        $timeExecution = $endTime - $startTime;
        echo 'Time execution : '.number_format($timeExecution, 4).' seconds'.PHP_EOL;
    }
}
