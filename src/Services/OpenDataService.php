<?php

namespace App\Services;

use App\Entity\EnergyType;
use App\Entity\Region;
use App\Repository\EnergyTypeRepository;
use App\Repository\OpenDataRawRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\String\Slugger\AsciiSlugger;

class OpenDataService
{
    private const OPENDATA_FOLDER = 'openDataFile';

    private string $openDataAbsolutePathFolder;

    /**
     * @var Region[]
     */
    private array $regionEntities;

    public function __construct(
        private readonly string $projectDir,
        private readonly EntityManagerInterface $em,
        private readonly RegionRepository $regionRepository,
        private readonly OpenDataRawRepository $openDataRawRepository,
        private EnergyTypeRepository $energyTypeRepository,
        private AsciiSlugger $slugger = new AsciiSlugger()
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
        // Import all raw datas
        $this->openDataRawRepository->insertDataWithLoadDataInfileSQLFunction($this->openDataAbsolutePathFolder.'/'.$filename);

        // Extract and save non-existent regions
        $this->regionEntities = $this->regionHandler();
    }

    public function processingWithDQL(int $maxDatas): void
    {
        // Retrieve all energyType entities
        $energyTypeEntities = $this->energyTypeRepository->getAllWithNameSlugIndex();

        // handle datas
        $this->openDataRawRepository->handleDataAfterLoadDataInfileDQL($this->regionEntities, $energyTypeEntities, $maxDatas);
    }

    public function processingWithSQL(): void
    {
    }

    /**
     * @return array|Region[]
     */
    private function regionHandler(): array
    {
        $regionEntities = [];

        // Extract
        $results = $this->openDataRawRepository->extractRegionFromRaw();

        // Check if region already exist or not
        foreach ($results as $result) {
            $slugName = $this->slugger->slug($result['region']);
            $region = $this->regionRepository->findOneByNameSlug($slugName);
            if (!$region) {
                $region = (new Region())
                    ->setName($result['region'])
                    ->setCodeInsee($result['codeInsee'])
                ;
            }
            $this->em->persist($region);

            $regionEntities[] = $region;
        }

        $this->em->flush();

        return $regionEntities;
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
