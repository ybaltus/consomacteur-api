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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');

        if ($filename) {
            $io->note(sprintf('Filename : %s', $filename));

            $fileExist = $this->openDataService->checkFileExists($filename);

            if (!$fileExist) {
                $io->warning(sprintf('The file %s does not exist.', $filename));

                return Command::FAILURE;
            }

            $this->openDataService->insertDatasFromCsvFile($filename);
        }

        $io->success('Successful data import !');

        return Command::SUCCESS;
    }
}
