<?php

namespace App\Command;

use App\Message\ImportOpenDataCsv;
use App\Services\OpenDataService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'import:open-data-csv-messenger',
    description: 'Import energy consumption csv from OpenData Réseaux-Energies with Symfony Messenger and SystemD',
)]
class ImportOpenDataWithMessengerCommand extends Command
{
    public function __construct(
        private readonly OpenDataService $openDataService,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filename', InputArgument::OPTIONAL, 'The filename')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename') ?? 'eco2mix-regional-cons-def.csv';

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

            // Dispatch
            $this->messageBus->dispatch(new ImportOpenDataCsv($filename));
        }

        $io->success('Sending the message successfully!');

        return Command::SUCCESS;
    }
}
