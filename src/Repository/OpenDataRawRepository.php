<?php

namespace App\Repository;

use App\Entity\EnergyConsumption;
use App\Entity\EnergyType;
use App\Entity\OpenDataRaw;
use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OpenDataRaw>
 *
 * @method OpenDataRaw|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpenDataRaw|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpenDataRaw[]    findAll()
 * @method OpenDataRaw[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpenDataRawRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpenDataRaw::class);
    }

    public function insertDataWithLoadDataInfileSQLFunction(string $fileAbsolutePath): void
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(OpenDataRaw::class)->getTableName();

        // Truncate the table
        $truncateQuery = sprintf('TRUNCATE TABLE %s', $tableName);
        $conn->executeQuery($truncateQuery);

        // Insert file datas
        $insertQuery = sprintf("
            LOAD DATA LOCAL INFILE '%s'
            INTO TABLE %s
            CHARACTER SET UTF8
            FIELDS TERMINATED BY ';'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (@col0, @col1, @col2, @col3, @col4, @col5, @col6, @col7, @col8, @col9, @col10, @col11, @col12, @col13, @col14, @col15, @col16, @col17, @col18, @col19, @col20, @col21, @col22, @col23, @col24, @col25, @col26, @col27, @col28, @col29, @col30)
            SET code_insee = @col0,
                region = @col1,
                measure_date= @col5,
                consum_electric= @col6,
                consum_thermic= @col7,
                consum_nuclear= @col8,
                consum_wind= @col9,
                consum_solar= @col10,
                consum_hydraulic= @col11
            ", $fileAbsolutePath, $tableName);

        $conn->executeQuery($insertQuery);
    }

    /**
     * @return mixed[]
     */
    public function extractRegionFromRaw(): array
    {
        $query = $this->createQueryBuilder('o')
            ->select('o.region, o.codeInsee')
            ->orderBy('o.region')
            ->distinct(true)
            ->getQuery()
        ;

        return $query->getResult();
    }

    /**
     * @param Region[]                  $regionEntities
     * @param array<string, EnergyType> $energyTypeEntities
     */
    public function handleDataAfterLoadDataInfileDQL(array $regionEntities, array $energyTypeEntities, int $maxDatas): void
    {
        $em = $this->getEntityManager();
        $qb = $this->createQueryBuilder('o')
            ->setMaxResults($maxDatas)
            ->getQuery();

        foreach ($qb->toIterable([], Query::HYDRATE_ARRAY) as $key => $row) {
            // do stuff with the data in the row

            // Get Region
            $region = $this->searchRegion($regionEntities, $row['region']);

            // Electric EnergyConsumtion
            $electric = $this->newEntityByEnergyType(
                $row['measureDate'],
                $row['consumElectric'],
                $region,
                $energyTypeEntities['electrique']
            );
            $em->persist($electric);

            // Eolien EnergyConsumtion
            $eolien = $this->newEntityByEnergyType(
                $row['measureDate'],
                $row['consumWind'],
                $region,
                $energyTypeEntities['eolien']
            );
            $em->persist($eolien);

            // Hydraulic EnergyConsumtion
            $hydraulique = $this->newEntityByEnergyType(
                $row['measureDate'],
                $row['consumHydraulic'],
                $region,
                $energyTypeEntities['hydraulique']
            );
            $em->persist($hydraulique);

            // Nuclear EnergyConsumtion
            $nuclear = $this->newEntityByEnergyType(
                $row['measureDate'],
                $row['consumNuclear'],
                $region,
                $energyTypeEntities['nucleaire']
            );
            $em->persist($nuclear);

            // Solar EnergyConsumtion
            $solar = $this->newEntityByEnergyType(
                $row['measureDate'],
                $row['consumSolar'],
                $region,
                $energyTypeEntities['solaire']
            );
            $em->persist($solar);

            // Thermic EnergyConsumtion
            $thermic = $this->newEntityByEnergyType(
                $row['measureDate'],
                $row['consumThermic'],
                $region,
                $energyTypeEntities['thermique']
            );
            $em->persist($thermic);

            // Save in DB
            if ($key % 100) {
                $em->flush();
            }

            // detach from Doctrine, so that it can be Garbage-Collected immediately
            //            $em->detach($row);
        }
        $em->flush();
    }

    public function handleDataAfterLoadDataInfileSQL(int $maxDatas, string $energyTable, string $colEnergy): void
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(OpenDataRaw::class)->getTableName();

        // Truncate the table
        $truncateQuery = sprintf('TRUNCATE TABLE %s', $energyTable);
        $conn->executeQuery($truncateQuery);

        // Insert new datas
        $sql = sprintf('
            INSERT INTO %s (code_insee, region, measure_date, measure_value)
            SELECT code_insee, region, measure_date, %s
            FROM %s
            ', $energyTable, $colEnergy, $tableName);

        if (-1 !== $maxDatas) {
            $sql .= "LIMIT $maxDatas";
        }

        $conn->executeQuery($sql);
    }

    /**
     * @param Region[] $regionEntities
     */
    private function searchRegion(array $regionEntities, string $needle): ?Region
    {
        foreach ($regionEntities as $region) {
            if (str_contains($region->getName(), $needle)) {
                return $region;
            }
        }

        return null;
    }

    private function newEntityByEnergyType(\DateTimeImmutable $measureDate, float $measureValue, Region $region, EnergyType $energyType): EnergyConsumption
    {
        return (new EnergyConsumption())
                ->setMeasureDate($measureDate)
                ->setMeasureValue($measureValue)
                ->setRegion($region)
                ->setEnergyType($energyType)
        ;
    }
}
