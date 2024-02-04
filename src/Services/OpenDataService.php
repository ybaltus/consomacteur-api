<?php

namespace App\Services;

use App\Repository\OpenDataRawRepository;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

readonly class OpenDataService
{
    private const OPENDATA_FOLDER = 'openDataFile';

    private string $openDataAbsolutePathFolder;

    public function __construct(
        private string $projectDir,
        private readonly OpenDataRawRepository $openDataRawRepository,
    ) {
        // Init OpenData folder path
        $this->openDataAbsolutePathFolder = Path::makeAbsolute(self::OPENDATA_FOLDER, $this->projectDir);
    }

    public function checkFileExists(string $filename): bool
    {
        $filesystem = new Filesystem();
        try {
            // Create Open Data folder if not exists
            $filesystem->mkdir($this->openDataAbsolutePathFolder);

            // Accept only csv file
            if (!$this->acceptOnlyCsvExtension($filename)) {
                return false;
            }

            // Check file exist
            return $filesystem->exists($this->openDataAbsolutePathFolder.'/'.$filename);
        } catch (IOExceptionInterface $exception) {
            echo sprintf('An error occurred while checking your file at %s', $exception->getPath());

            return false;
        }
    }

    public function insertDatasFromCsvFile(string $filename): void
    {
        // Import all datas
        $this->openDataRawRepository->insertDataWithLoadDataInfileSQLFunction($this->openDataAbsolutePathFolder.'/'.$filename);
    }

    private function acceptOnlyCsvExtension(string $filename): bool
    {
        $pathInfo = pathinfo($this->openDataAbsolutePathFolder.'/'.$filename);

        if (!isset($pathInfo['extension'])) {
            echo sprintf('The file extension is not csv or is missing. %s', PHP_EOL);

            return false;
        }

        return true;
    }
}
