<?php

namespace App\Services;

use App\Entity\Electric;
use App\Entity\Eolien;
use App\Entity\Hydraulic;
use App\Entity\Nuclear;
use App\Entity\Region;
use App\Entity\Solar;
use App\Entity\Thermic;
use App\Repository\ElectricRepository;
use App\Repository\EnergyTypeRepository;
use App\Repository\EolienRepository;
use App\Repository\HydraulicRepository;
use App\Repository\NuclearRepository;
use App\Repository\OpenDataRawRepository;
use App\Repository\RegionRepository;
use App\Repository\SolarRepository;
use App\Repository\ThermicRepository;
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
        private readonly EnergyTypeRepository $energyTypeRepository,
        private readonly ElectricRepository $electricRepository,
        private readonly ThermicRepository $thermicRepository,
        private readonly HydraulicRepository $hydraulicRepository,
        private readonly SolarRepository $solarRepository,
        private readonly NuclearRepository $nuclearRepository,
        private readonly EolienRepository $eolienRepository,
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

    public function insertDatasFromCsvFileWithMessenger(string $filename): void
    {
        $startTime = microtime(true);
        $handle = fopen($this->openDataAbsolutePathFolder.'/'.$filename, 'r');
        $entries = [];

        if (!$handle) {
            return;
        }

        while (false !== ($data = fgetcsv($handle, null, ';'))) {
            $entries[] = $data;
        }

        if (!empty($entries)) {
            // Remove headers
            array_shift($entries);

            // Reset tables
            $this->electricRepository->truncateTableByName('electric');
            $this->eolienRepository->truncateTableByName('eolien');
            $this->hydraulicRepository->truncateTableByName('hydraulic');
            $this->nuclearRepository->truncateTableByName('nuclear');
            $this->solarRepository->truncateTableByName('solar');
            $this->thermicRepository->truncateTableByName('thermic');

            $batchSize = 100;

            // Save the energies
            foreach ($entries as $key => $entry) {
                // Required data
                $codeInsee = intval($entry[0]);
                $region = $entry[1];
                $measureDate = new \DateTimeImmutable($entry[5]);
                $electricValue = intval($entry[6]);
                $thermicValue = intval($entry[7]);
                $nuclearValue = intval($entry[8]);
                $eolienValue = intval($entry[9]);
                $solarValue = intval($entry[10]);
                $hydraulicValue = intval($entry[11]);

                // Electric entity
                $electricEntity = $this->createNewEnergyEntity(
                    $codeInsee,
                    $region,
                    $measureDate,
                    $electricValue,
                    Electric::class
                );

                $this->em->persist($electricEntity);

                // Thermic entity
                $thermicEntity = $this->createNewEnergyEntity(
                    $codeInsee,
                    $region,
                    $measureDate,
                    $thermicValue,
                    Thermic::class
                );

                $this->em->persist($thermicEntity);

                // Nuclear entity
                $nuclearEntity = $this->createNewEnergyEntity(
                    $codeInsee,
                    $region,
                    $measureDate,
                    $nuclearValue,
                    Nuclear::class
                );

                $this->em->persist($nuclearEntity);

                // Eolien entity
                $eolienEntity = $this->createNewEnergyEntity(
                    $codeInsee,
                    $region,
                    $measureDate,
                    $eolienValue,
                    Eolien::class
                );

                $this->em->persist($eolienEntity);

                // Solar entity
                $solarEntity = $this->createNewEnergyEntity(
                    $codeInsee,
                    $region,
                    $measureDate,
                    $solarValue,
                    Solar::class
                );

                $this->em->persist($solarEntity);

                // Hydraulic entity
                $hydraulicEntity = $this->createNewEnergyEntity(
                    $codeInsee,
                    $region,
                    $measureDate,
                    $hydraulicValue,
                    Hydraulic::class
                );

                $this->em->persist($hydraulicEntity);

                if (0 === ($key % $batchSize)) {
                    $this->em->flush();

                    // The clear() method detaches all entities from the EntityManager, which can help prevent memory issues
                    $this->em->clear();
                }
            }

            $this->em->flush();
            $this->em->clear();

            $endTime = microtime(true);
            $timeExecution = $endTime - $startTime;
            var_dump('Time execution : '.number_format($timeExecution, 4));
        }
    }

    public function insertDatasFromCsvFileWithLoadDataInfile(string $filename, bool $isByEnergy = false): void
    {
        // Import all raw datas
        if (!$isByEnergy) {
            $this->openDataRawRepository->insertDataWithLoadDataInfileSQLFunction($this->openDataAbsolutePathFolder.'/'.$filename);
        } else {
            // Electric
            $this->electricRepository->insertDataWithLoadDataInfileSQLFunctionPerEnergy(
                $this->openDataAbsolutePathFolder.'/'.$filename,
                'electric',
                '@col6'
            );

            // Thermic
            $this->thermicRepository->insertDataWithLoadDataInfileSQLFunctionPerEnergy(
                $this->openDataAbsolutePathFolder.'/'.$filename,
                'thermic',
                '@col7'
            );

            // Nuclear
            $this->nuclearRepository->insertDataWithLoadDataInfileSQLFunctionPerEnergy(
                $this->openDataAbsolutePathFolder.'/'.$filename,
                'nuclear',
                '@col8'
            );

            // Eolien
            $this->eolienRepository->insertDataWithLoadDataInfileSQLFunctionPerEnergy(
                $this->openDataAbsolutePathFolder.'/'.$filename,
                'eolien',
                '@col9'
            );

            // Solar
            $this->solarRepository->insertDataWithLoadDataInfileSQLFunctionPerEnergy(
                $this->openDataAbsolutePathFolder.'/'.$filename,
                'solar',
                '@col10'
            );

            // Hydraulic
            $this->hydraulicRepository->insertDataWithLoadDataInfileSQLFunctionPerEnergy(
                $this->openDataAbsolutePathFolder.'/'.$filename,
                'hydraulic',
                '@col11'
            );
        }

        // Extract and save non-existent regions
        $this->regionEntities = $this->regionHandler();
    }

    public function processingWithDQLAfterLoadDataInfile(int $maxDatas): void
    {
        // Retrieve all energyType entities
        $energyTypeEntities = $this->energyTypeRepository->getAllWithNameSlugIndex();

        // handle datas
        $this->openDataRawRepository->handleDataAfterLoadDataInfileDQL($this->regionEntities, $energyTypeEntities, $maxDatas);
    }

    public function processingWithSQLAfterLoadDataInfile(int $maxDatas): void
    {
        // Electric
        $this->openDataRawRepository->handleDataAfterLoadDataInfileSQL($maxDatas, 'electric', 'consum_electric');

        // Eolien
        $this->openDataRawRepository->handleDataAfterLoadDataInfileSQL($maxDatas, 'eolien', 'consum_wind');

        // Hydraulic
        $this->openDataRawRepository->handleDataAfterLoadDataInfileSQL($maxDatas, 'hydraulic', 'consum_hydraulic');

        // Nuclear
        $this->openDataRawRepository->handleDataAfterLoadDataInfileSQL($maxDatas, 'nuclear', 'consum_nuclear');

        // Solar
        $this->openDataRawRepository->handleDataAfterLoadDataInfileSQL($maxDatas, 'solar', 'consum_solar');

        // Thermic
        $this->openDataRawRepository->handleDataAfterLoadDataInfileSQL($maxDatas, 'thermic', 'consum_thermic');
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

    private function createNewEnergyEntity(
        int $codeInsee,
        string $region,
        \DateTimeImmutable $measureDate,
        int $measureValue,
        string $entityName
    ): object {
        return (new $entityName())
            ->setCodeInsee($codeInsee)
            ->setRegion($region)
            ->setMeasureDate($measureDate)
            ->setMeasureValue($measureValue)
        ;
    }
}
